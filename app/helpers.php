<?php

use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\InvoiceSetting;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Setting;
use App\Models\State;
use App\Models\Tax;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;

if (! function_exists('getLogInUser')) {
    /**
     * @return Authenticatable|null
     */
    function getLogInUser()
    {
        return Auth::user();
    }
}

if (! function_exists('getAppName')) {
    /**
     * @return mixed
     */
    function getAppName()
    {
        /** @var Setting $appName */
        static $appName;
        if (empty($appName)) {
            $appName = Setting::where('key', '=', 'app_name')->first();
        }

        return $appName->value ?? 'BillBridge';
    }
}

if (! function_exists('getLogoUrl')) {

    function getLogoUrl(): string
    {
        static $logoUrl;

        if (empty($logoUrl)) {
            $appLogo = Setting::where('key', '=', 'app_logo')->first();
            
            if ($appLogo) {
                // Check if there are media files attached
                $media = $appLogo->media->first();
                if (!empty($media)) {
                    $logoUrl = $media->getFullUrl();
                } else {
                    // Use the value from database or fallback to default
                    $logoPath = $appLogo->value ?? 'assets/images/billbridge.png';
                    $logoUrl = asset($logoPath);
                }
            } else {
                // Fallback if no setting exists
                $logoUrl = asset('assets/images/billbridge.png');
            }
        }

        return $logoUrl;
    }
}

if (! function_exists('getPDFLogoUrl')) {

    function getPDFLogoUrl(): string
    {
        static $logoUrl;

        if (empty($logoUrl)) {
            $appLogo = Setting::where('key', '=', 'app_logo')->first();
            
            if ($appLogo && !empty($appLogo->logo_url)) {
                $logoPath = $appLogo->logo_url;
                
                // Check if it's a URL or a local path
                if (filter_var($logoPath, FILTER_VALIDATE_URL)) {
                    // If it's a URL, check if it's a local URL
                    $localUrl = parse_url($logoPath);
                    $currentUrl = parse_url(config('app.url'));
                    
                    if (isset($localUrl['host']) && ($localUrl['host'] === ($currentUrl['host'] ?? 'localhost') || 
                        $localUrl['host'] === '127.0.0.1' || $localUrl['host'] === 'localhost')) {
                        // Convert to local file path
                        $relativePath = ltrim($localUrl['path'], '/');
                        $localPath = public_path($relativePath);
                        
                        if (file_exists($localPath)) {
                            $imageData = file_get_contents($localPath);
                            $imageType = pathinfo($localPath, PATHINFO_EXTENSION);
                            $logoUrl = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        } else {
                            // Fallback to default logo
                            $defaultLogoPath = public_path('assets/images/billbridge.png');
                            if (file_exists($defaultLogoPath)) {
                                $imageData = file_get_contents($defaultLogoPath);
                                $logoUrl = 'data:image/png;base64,' . base64_encode($imageData);
                            } else {
                                $logoUrl = '';
                            }
                        }
                    } else {
                        // External URL, use HTTP request with timeout
                        try {
                            $imageData = Http::timeout(10)->get($logoPath)->body();
                            $imageType = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoUrl = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                        } catch (\Exception $e) {
                            // Log error and use default logo
                            \Log::warning('Failed to fetch logo image: ' . $e->getMessage());
                            $defaultLogoPath = public_path('assets/images/billbridge.png');
                            if (file_exists($defaultLogoPath)) {
                                $imageData = file_get_contents($defaultLogoPath);
                                $logoUrl = 'data:image/png;base64,' . base64_encode($imageData);
                            } else {
                                $logoUrl = '';
                            }
                        }
                    }
                } else {
                    // It's already a local path
                    $localPath = public_path($logoPath);
                    if (file_exists($localPath)) {
                        $imageData = file_get_contents($localPath);
                        $imageType = pathinfo($localPath, PATHINFO_EXTENSION);
                        $logoUrl = 'data:image/' . $imageType . ';base64,' . base64_encode($imageData);
                    } else {
                        // Fallback to default logo
                        $defaultLogoPath = public_path('assets/images/infyom.png');
                        if (file_exists($defaultLogoPath)) {
                            $imageData = file_get_contents($defaultLogoPath);
                            $logoUrl = 'data:image/png;base64,' . base64_encode($imageData);
                        } else {
                            $logoUrl = '';
                        }
                    }
                }
            } else {
                // No logo setting found, use default
                $defaultLogoPath = public_path('assets/images/infyom.png');
                if (file_exists($defaultLogoPath)) {
                    $imageData = file_get_contents($defaultLogoPath);
                    $logoUrl = 'data:image/png;base64,' . base64_encode($imageData);
                } else {
                    $logoUrl = '';
                }
            }
        }

        return $logoUrl;
    }
}

if (! function_exists('getUserLanguages')) {
    function getUserLanguages(): array
    {
        $language = User::LANGUAGES;
        asort($language);

        return $language;
    }
}

if (! function_exists('getCurrentLanguageName')) {

    function getCurrentLanguageName(): mixed
    {
        return Auth::user()->language;
    }
}

if (! function_exists('getManualPayment')) {

    function getManualPayment(): mixed
    {
        static $manualPayment;

        if (empty($manualPayment)) {
            $manualPayment = Setting::where('key', '=', 'payment_auto_approved')->first()->value;
        }

        return $manualPayment;
    }
}

if (! function_exists('getInvoicePaidAmount')) {
    /**
     * @return float|int
     */
    function getInvoicePaidAmount($invoiceId)
    {
        $paid = 0;

        $invoice = Invoice::whereId($invoiceId)->with('payments')->firstOrFail();

        if ($invoice->status != Invoice::PAID) {
            foreach ($invoice->payments as $payment) {
                if ($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED) {
                    continue;
                }
                $paid += $payment->amount;
            }
        } else {
            $paid += $invoice->final_amount;
        }

        return $paid;
    }
}

if (! function_exists('getInvoiceDueAmount')) {
    /**
     * @return float|\Illuminate\Database\Eloquent\HigherOrderBuilderProxy|int|mixed|null
     */
    function getInvoiceDueAmount($invoiceId)
    {
        $paid = 0;
        $invoice = Invoice::whereId($invoiceId)->with('payments')->firstOrFail();

        foreach ($invoice->payments as $payment) {
            if ($payment->payment_mode == Payment::MANUAL && $payment->is_approved !== Payment::APPROVED) {
                continue;
            }
            $paid += $payment->amount;
        }

        return $invoice->status != Invoice::PAID ? $invoice->final_amount - $paid : 0;
    }
}

if (! function_exists('getLogInUserId')) {

    function getLogInUserId(): int
    {
        return Auth::id();
    }
}


if (! function_exists('removeCommaFromNumbers')) {
    /**
     * @return string|string[]
     */
    function removeCommaFromNumbers($number): array|string
    {
        return (gettype($number) == 'string' && ! empty($number)) ? str_replace(',', '', $number) : $number;
    }
}

if (! function_exists('getStates')) {

    function getStates($countryId): array
    {
        return State::where('country_id', $countryId)->toBase()->pluck('name', 'id')->toArray();
    }
}

if (! function_exists('getCities')) {

    function getCities($stateId): array
    {
        return City::where('state_id', $stateId)->pluck('name', 'id')->toArray();
    }
}

if (! function_exists('getCurrentTimeZone')) {

    function getCurrentTimeZone(): mixed
    {
        /** @var Setting $currentTimezone */
        static $currentTimezone;

        try {
            if (empty($currentTimezone)) {
                $currentTimezone = Setting::where('key', 'time_zone')->first();
            }
            if ($currentTimezone != null) {
                return $currentTimezone->value;
            } else {
                return null;
            }
        } catch (Exception $exception) {
            return 'Asia/Kolkata';
        }
    }
}

if (! function_exists('getCurrencies')) {
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function getCurrencies(): Illuminate\Database\Eloquent\Collection
    {
        return Currency::all();
    }
}

if (! function_exists('getCurrencySymbol')) {

    function getCurrencySymbol(): mixed
    {
        static $currencySymbol;
        /** @var Setting $currencySymbol */
        if (empty($currencySymbol)) {
            $currencySymbol = Currency::where('id', getSettingValue('current_currency'))->pluck('icon')->first();
        }

        return $currencySymbol;
    }
}

if (! function_exists('getInvoiceNoPrefix')) {
    /**
     * @return mixed
     */
    function getInvoiceNoPrefix()
    {
        /** @var Setting $invoiceNoPrefix */
        $invoiceNoPrefix = Setting::where('key', '=', 'invoice_no_prefix')->first();

        return $invoiceNoPrefix->value;
    }
}

if (! function_exists('getInvoiceNoSuffix')) {
    /**
     * @return mixed
     */
    function getInvoiceNoSuffix()
    {
        /** @var Setting $invoiceNoSuffix */
        $invoiceNoSuffix = Setting::where('key', '=', 'invoice_no_suffix')->first();

        return $invoiceNoSuffix->value;
    }
}

if (! function_exists('getDefaultTax')) {
    function getDefaultTax()
    {
        return Tax::where('is_default', '=', '1')->first()->id ?? null;
    }
}

if (! function_exists('setStripeApiKey')) {
    function setStripeApiKey()
    {
        $stripeSecretKey = config('services.stripe.secret_key');
        $stripeSecret = getSettingValue('stripe_secret');
        isset($stripeSecret) ? Stripe::setApiKey($stripeSecret) : Stripe::setApiKey($stripeSecretKey);
    }
}

// current date format
if (! function_exists('currentDateFormat')) {

    function currentDateFormat(): mixed
    {
        static $dateFormat;
        /** @var Setting $dateFormat */
        if (empty($dateFormat)) {
            $dateFormat = Setting::where('key', '=', 'date_format')->first();
        }

        return $dateFormat->value;
    }
}

if (! function_exists('momentJsCurrentDateFormat')) {

    function momentJsCurrentDateFormat(): string
    {
        $key = Setting::DateFormatArray[currentDateFormat()];

        return $key;
    }
}

if (! function_exists('addNotification')) {
    /**
     * @param  array  $data
     */
    function addNotification($data)
    {
        Log::info($data);
        $notificationRecord = [
            'type' => $data[0],
            'user_id' => $data[1],
            'title' => $data[2],
        ];

        if ($user = User::find($data[1])) {
            Notification::create($notificationRecord);
        }
    }
}

if (! function_exists('getNotification')) {
    function getNotification()
    {
        static $notification;
        /** @var Setting $notification */
        if (empty($notification)) {
            $notification = Notification::whereUserId(Auth::id())
                ->where('read_at', null)
                ->orderByDesc('created_at')
                ->toBase()
                ->get();
        }

        return $notification;
    }
}

if (! function_exists('getAllNotificationUser')) {
    /**
     * @param  array  $data
     * @return array
     */
    function getAllNotificationUser($data)
    {
        return array_filter($data, function ($key) {
            return $key != getLogInUserId();
        }, ARRAY_FILTER_USE_KEY);
    }
}

if (! function_exists('getNotificationIcon')) {
    /**
     * @return string|void
     */
    function getNotificationIcon($notificationType)
    {
        switch ($notificationType) {
            case 1:
            case 2:
                return 'heroicon-s-document-text';
            case 3:
                return 'heroicon-s-banknotes';
            case 4:
                return 'fas fa-file-invoice';
            case 5:
                return 'heroicon-s-chat-bubble-left';
            case 6:
                return 'heroicon-s-chat-bubble-left';
        }
    }
}

if (! function_exists('getAdminUser')) {
    /**
     * @return User|Builder|Model|object|null
     */
    function getAdminUser()
    {
        /** @var User $user */
        $user = User::with([
            'roles' => function ($q) {
                $q->where('name', 'Admin');
            },
        ])
            ->first();

        return $user;
    }
}

if (! function_exists('canDelete')) {
    /**
     * @return bool
     */
    function canDelete(array $models, string $columnName, int $id)
    {
        foreach ($models as $model) {
            $result = $model::where($columnName, $id)->exists();

            if ($result) {
                return true;
            }
        }

        return false;
    }
}

if (! function_exists('canAccess')) {
    /**
     * @return bool
     */
    function canAccess($model, string $columnName, int $id)
    {
        $invoice = Invoice::findOrFail($id);
        $userID = $invoice->client->user_id;

        if (getLogInUserId() == $userID) {
            return true;
        }

        return false;
    }
}

if (! function_exists('numberFormat')) {
    function numberFormat(?float $num, int $decimals = 2)
    {
        /** @var Setting $decimal_separator */
        /** @var Setting $thousands_separator */
        static $decimal_separator;
        static $thousands_separator;
        if (empty($decimal_separator) || empty($thousands_separator)) {
            $decimal_separator = getSettingValue('decimal_separator');
            $thousands_separator = getSettingValue('thousand_separator');
        }

        return number_format($num, $decimals, $decimal_separator, $thousands_separator);
    }
}

if (! function_exists('getSettingValue')) {
    /**
     * @return mixed
     */
    function getSettingValue($keyName)
    {
        $key = 'setting' . '-' . $keyName;
        static $settingValues;
        if (isset($settingValues[$key])) {
            return $settingValues[$key];
        }

        /** @var Setting $setting */
        $setting = Setting::where('key', '=', $keyName)->first();
        $settingValues[$key] = $setting->value;
        return $setting->value;
    }
}

if (! function_exists('getPaymentGateway')) {
    function getPaymentGateway($keyName)
    {
        $key = $keyName;
        static $settingValues;

        if (isset($settingValues[$key])) {
            return $settingValues[$key];
        }
        /** @var Setting $setting */
        $setting = Setting::where('key', '=', $keyName)->first();

        if ($setting->value !== '') {
            $settingValues[$key] = $setting->value;
        } else {
            $settingValues[$key] = (env($key) !== '') ? env($key) : '';
        }

        return $setting->value;
    }
}

if (! function_exists('getCurrencyCode')) {
    /**
     * @return mixed
     */
    function getCurrencyCode()
    {
        $currencyId = Setting::where('key', 'current_currency')->value('value');
        $currencyCode = Currency::whereId($currencyId)->first();

        return $currencyCode->code;
    }
}

if (! function_exists('getInvoiceCurrencyCode')) {
    function getInvoiceCurrencyCode($currencyId)
    {
        if (empty($currencyId)) {
            $currencyId = getSettingValue('current_currency');
        }

        $invoiceCurrencyCode = Currency::whereId($currencyId)->first();

        return $invoiceCurrencyCode->code;
    }
}

if (! function_exists('getInvoiceCurrencyIcon')) {
    function getInvoiceCurrencyIcon($currencyId)
    {
        if (empty($currencyId)) {
            $currencyId = getSettingValue('current_currency');
        }

        $invoiceCurrencyCode = Currency::whereId($currencyId)->first();

        return $invoiceCurrencyCode->icon ?? '₹';
    }
}

if (! function_exists('getCurrentVersion')) {
    /**
     * @return mixed
     */
    function getCurrentVersion()
    {
        $composerFile = file_get_contents('../composer.json');
        $composerData = json_decode($composerFile, true);
        $currentVersion = $composerData['version'];

        return $currentVersion;
    }
}

if (! function_exists('formatTotalAmount')) {
    /**
     * @param  int  $precision
     */
    function formatTotalAmount($totalAmount, $precision = 2)
    {
        if ($totalAmount < 900) {
            // 0 - 900
            $numberFormat = number_format($totalAmount, $precision);
            $suffix = '';
        } else {
            if ($totalAmount < 900000) {
                // 0.9k-850k
                $numberFormat = number_format($totalAmount / 1000, $precision);
                $suffix = 'K';
            } else {
                if ($totalAmount < 900000000) {
                    // 0.9m-850m
                    $numberFormat = number_format($totalAmount / 1000000, $precision);
                    $suffix = 'M';
                } else {
                    if ($totalAmount < 900000000000) {
                        // 0.9b-850b
                        $numberFormat = number_format($totalAmount / 1000000000, $precision);
                        $suffix = 'B';
                    } else {
                        // 0.9t+
                        $numberFormat = number_format($totalAmount / 1000000000000, $precision);
                        $suffix = 'T';
                    }
                }
            }
        }

        // Remove unnecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotZero = '.' . str_repeat('0', $precision);
            $numberFormat = str_replace($dotZero, '', $numberFormat);
        }

        return $numberFormat . $suffix;
    }
}

if (! function_exists('getCurrencyAmount')) {
    /**
     * @param  false  $formatting
     * @return string
     */
    function getCurrencyAmount($amount, $formatting = false)
    {
        static $currencyPosition;
        if (empty($currencyPosition)) {
            $currencyPosition = getSettingValue('currency_after_amount');
        }

        $currencySymbol = getCurrencySymbol();
        $formattedAmount = $formatting ? numberFormat($amount) : formatTotalAmount($amount);
        if ($currencyPosition) {
            return $formattedAmount . ' ' . $currencySymbol;
        }

        return $currencySymbol . ' ' . $formattedAmount;
    }
}

if (! function_exists('getInvoiceCurrencyAmount')) {
    /**
     * @param  false  $formatting
     */
    function getInvoiceCurrencyAmount($amount, $currencyId, $formatting = false): string
    {
        static $currencyPosition;
        static $currencySymbols;
        if (empty($currencyPosition)) {
            $currencyPosition = getSettingValue('currency_after_amount');
        }

        if (empty($currencyId)) {
            $currencyId = getSettingValue('current_currency');
        }

        if (empty($currencySymbols)) {
            $currencySymbols = Currency::toBase()->pluck('icon', 'id')->toArray();
        }

        $formattedAmount = $formatting ? numberFormat($amount) : formatTotalAmount($amount);
        $currencySymbol = isset($currencySymbols) && !empty($currencySymbols[$currencyId]) ? $currencySymbols[$currencyId] : '₹';
        if ($currencyPosition) {
            return $formattedAmount . ' ' . $currencySymbol;
        }

        return $currencySymbol . ' ' . $formattedAmount;
    }
}

if (! function_exists('checkContactUniqueness')) {
    function checkContactUniqueness($value, $regionCode, $exceptId = null): bool
    {
        $recordExists = User::where('contact', $value)->where('region_code', $regionCode)->get();
        if ($exceptId) {
            $recordExists = $recordExists->where('id', '!=', $exceptId);
        }
        if ($recordExists->exists()) {
            return true;
        }

        return false;
    }
}

if (! function_exists('getPayPalSupportedCurrencies')) {

    function getPayPalSupportedCurrencies(): array
    {
        return [
            'AUD',
            'BRL',
            'CAD',
            'CNY',
            'CZK',
            'DKK',
            'EUR',
            'HKD',
            'HUF',
            'ILS',
            'JPY',
            'MYR',
            'MXN',
            'TWD',
            'NZD',
            'NOK',
            'PHP',
            'PLN',
            'GBP',
            'RUB',
            'SGD',
            'SEK',
            'CHF',
            'THB',
            'USD',
        ];
    }
}

if (! function_exists('getMonthlyData')) {
    function getMonthlyData(): string
    {
        $carbon = Carbon::now();
        $startDate = $carbon->startOfMonth()->format('Y-m-d');
        $endDate = $carbon->endOfMonth()->format('Y-m-d');

        return $startDate . ' - ' . $endDate;
    }
}

if (! function_exists('getInvoiceSettingTemplateId')) {
    /**
     * @return string
     */
    function getInvoiceSettingTemplateId()
    {
        $setting = Setting::where('key', 'default_invoice_template')->first();
        $invoiceSetting = InvoiceSetting::where('key', $setting->value)->first();

        return $invoiceSetting->id ?? null;
    }
}

if (! function_exists('getVatNoLabel')) {
    function getVatNoLabel()
    {
        $vatNoLabel = Setting::where('key', 'vat_no_label')->first()->value ?? 'GSTIN';

        return $vatNoLabel;
    }
}

if (!function_exists('checkLanguageSession')) {
    function checkLanguageSession()
    {
        if (Session::has('languageName')) {
            return Session::get('languageName');
        } else {
            return getDefaultLanguage();
        }
    }
}

if (!function_exists('getDefaultLanguage')) {
    function getDefaultLanguage()
    {
        $defaultLanguage = Setting::where('key', 'default_language')->first()->value ?? 'en';

        return $defaultLanguage;
    }
}

if (!function_exists('getTranslatedData')) {
    function getTranslatedData($data)
    {
        $translatedDataArr = collect($data)->map(function ($value) {
            return __('messages.' . strtolower($value));
        });

        return $translatedDataArr;
    }
}

/**
 * @return mixed
 */
if (!function_exists('getCountry')) {
    function getCountry()
    {
        $country = Country::orderBy('name')->pluck('name', 'id')->toArray();

        return $country;
    }
}

/**
 * @return mixed
 */
if (!function_exists('getState')) {
    function getState()
    {
        $state = State::orderBy('name')->pluck('name', 'id')->toArray();

        return $state;
    }
}

if (!function_exists('getAvatarUrl')) {
    function getAvatarUrl()
    {
        return 'https://ui-avatars.com/api/';
    }
}

if (!function_exists('getRandomColor')) {
    function getRandomColor($userId)
    {
        $colors = ['329af0', 'fc6369', 'ffaa2e', '42c9af', '7d68f0'];
        $index = $userId % 5;

        return $colors[$index];
    }
}

if (!function_exists('getUserImageInitial')) {
    function getUserImageInitial($userId, $name)
    {
        return getAvatarUrl() . "?name=$name&size=100&rounded=true&color=fff&background=" . getRandomColor($userId);
    }
}


if (!function_exists('getSettingValue')) {
    function getSettingValue($keyName)
    {
        return Setting::where('key', $keyName)->first()->value ?? null;
    }
}

if (!function_exists('getMercadopagoSupportedCurrencies')) {
    function getMercadopagoSupportedCurrencies(): array
    {
        return [
            'ARS',
            'BRL',
            'CLP',
            'COP',
            'MXN',
            'PEN',
            'UYU'
        ];
    }
}
