<?php

namespace App\Filament\Clusters\Settings\Pages;

use Throwable;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Faker\Provider\ar_EG\Text;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use App\Repositories\SettingRepository;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\ToggleButtons;
use League\Flysystem\UnableToCheckFileExistence;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Tapp\FilamentCountryCodeField\Forms\Components\CountryCodeSelect;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\HtmlString;

class General extends Page
{

    protected static string $view = 'filament.clusters.settings.pages.general';
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?string $cluster = Settings::class;
    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.general');
    }

    public function getTitle(): string|Htmlable
    {
        return __('messages.setting.general');
    }

    public function mount(): void
    {
        $data = app(SettingRepository::class)->getSyncList();
        $this->data = $data;
        $this->form->fill($data);
    }

    public  function form(Form $form): Form
    {
        $form->model = Setting::with('media')->first();
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('app_name')
                                    ->required()
                                    ->label(__('messages.setting.app_name') . ':')
                                    ->placeholder(__('messages.setting.app_name'))
                                    ->validationAttribute(__('messages.setting.app_name')),
                                TextInput::make('company_name')
                                    ->required()
                                    ->placeholder(__('messages.setting.company_name'))
                                    ->label(__('messages.setting.company_name') . ':')
                                    ->validationAttribute(__('messages.setting.company_name')),
                                CountryCodeSelect::make('country_code')
                                    ->required()
                                    ->label(__('messages.setting.country_code') . ':')
                                    ->validationAttribute(__('messages.setting.country_code')),
                            ])->columns(3),
                        Group::make()
                            ->schema([
                                PhoneInput::make('company_phone')
                                    ->required()
                                    ->label(__('messages.setting.company_phone') . ':')
                                    ->validationAttribute(__('messages.setting.company_phone')),
                                Select::make('date_format')
                                    ->options(Setting::DateFormatArray)
                                    ->required()
                                    ->label(__('messages.setting.date_format') . ':')
                                    ->validationAttribute(__('messages.setting.date_format'))
                                    ->native(false),
                                Select::make('time_zone')
                                    ->options(self::getTimeZone())
                                    ->required()
                                    ->label(__('messages.setting.timezone') . ':')
                                    ->validationAttribute(__('messages.setting.timezone')),
                            ])->columns(3),
                        Group::make()
                            ->schema([
                                Toggle::make('payment_auto_approved')
                                    ->required()
                                    ->label(__('messages.setting.payment_auto_approved') . ':')
                                    ->validationAttribute(__('messages.setting.payment_auto_approved'))
                                    ->inline(false)
                                    ->helperText(__('messages.setting.auto_approve')),
                                ToggleButtons::make('time_format')
                                    ->options([
                                        '0' => __('messages.setting.12_hour'),
                                        '1' => __('messages.setting.24_hour'),
                                    ])
                                    ->required()
                                    ->label(__('messages.setting.time_format') . ':')
                                    ->validationAttribute(__('messages.setting.time_format'))
                                    ->inline(true),
                                ToggleButtons::make('mail_notification')
                                    ->options([
                                        '0' => __('messages.tax.yes'),
                                        '1' => __('messages.tax.no'),
                                    ])
                                    ->required()
                                    ->label(__('messages.setting.mail_notifications') . ':')
                                    ->validationAttribute(__('messages.setting.mail_notifications'))
                                    ->inline(true),
                                Group::make()
                                    ->schema([
                                        Placeholder::make('clear_cache')
                                            ->label(__('messages.clear_cache') . ':')
                                            ->content(new HtmlString(
                                                '<button
                                                    style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);"
                                                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 focus-visible:ring-custom-500/50 dark:bg-custom-500 dark:hover:bg-custom-400 dark:focus-visible:ring-custom-400/50"
                                                    type="button"
                                                    wire:loading.attr="disabled"
                                                    wire:click.prevent="optimizeCache">

                                                    <svg fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                                        class="animate-spin h-5 w-5 text-white"
                                                        wire:loading.delay.default=""
                                                        wire:target="optimizeCache">
                                                        <path clip-rule="evenodd" d="M12 19C15.866 19 19 15.866 19 12C19 8.13401 15.866 5 12 5C8.13401 5 5 8.13401 5 12C5 15.866 8.13401 19 12 19ZM12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" fill-rule="evenodd" fill="currentColor" opacity="0.2"></path>
                                                        <path d="M2 12C2 6.47715 6.47715 2 12 2V5C8.13401 5 5 8.13401 5 12H2Z" fill="currentColor"></path>
                                                    </svg>

                                                    <span class="fi-btn-label">' . __('messages.clear_cache') . '</span>
                                                </button>'
                                            )),
                                    ]),
                            ])->columns(4),
                        Group::make()
                            ->schema([
                                TextInput::make('country')
                                    ->label(__('messages.country.country') . ':')
                                    ->required(fn($get) => $get('show_additional_address_in_invoice') !== false)
                                    ->placeholder(__('messages.country.country')),
                                TextInput::make('state')
                                    ->label(__('messages.state.state') . ':')
                                    ->required(fn($get) => $get('show_additional_address_in_invoice') !== false)
                                    ->placeholder(__('messages.state.state')),
                                TextInput::make('city')
                                    ->label(__('messages.city.city') . ':')
                                    ->required(fn($get) => $get('show_additional_address_in_invoice') !== false)
                                    ->placeholder(__('messages.city.city')),
                            ])->columns(3),
                        Group::make()
                            ->schema([
                                TextInput::make('zipcode')
                                    ->label(__('messages.common.zipcode') . ':')
                                    ->required(fn($get) => $get('show_additional_address_in_invoice') !== false)
                                    ->placeholder(__('messages.common.zipcode')),
                                TextInput::make('fax_no')
                                    ->label(__('messages.invoice.fax_no') . ':')
                                    ->required(fn($get) => $get('show_additional_address_in_invoice') !== false)
                                    ->placeholder(__('messages.invoice.fax_no')),
                                TextInput::make('gst_no')
                                    ->label(__('messages.setting.gstin') . ':')
                                    ->required()
                                    ->placeholder(__('messages.setting.gstin')),
                            ])->columns(3),
                        Group::make()
                            ->schema([
                                Textarea::make('company_address')
                                    ->required()
                                    ->label(__('messages.setting.company_address') . ':')
                                    ->placeholder(__('messages.setting.company_address'))
                                    ->validationAttribute(__('messages.setting.company_address'))
                                    ->rows(4),
                                SpatieMediaLibraryFileUpload::make('app_logo')
                                    ->label(__('messages.setting.app_logo') . ':')
                                    ->validationAttribute(__('messages.setting.app_logo'))
                                    ->avatar()
                                    ->imageCropAspectRatio(null)
                                    ->imageEditor()
                                    ->disk(config('app.media_disk'))
                                    ->image()
                                    ->collection(Setting::PATH)
                                    ->saveUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string {
                                        $record = Setting::where('key', '=', 'app_logo')->first();
                                        if (! $record) {
                                            $record = Setting::create([
                                                'key' => 'app_logo',
                                                'value' => null,
                                            ]);
                                        }

                                        if (! method_exists($record, 'addMediaFromString')) {
                                            return $file;
                                        }

                                        try {
                                            if (! $file->exists()) {
                                                return null;
                                            }
                                        } catch (UnableToCheckFileExistence $exception) {
                                            return null;
                                        }

                                        $record->getMedia($component->getCollection() ?? 'default')
                                            ->whereNotIn('uuid', array_keys($component->getState() ?? []))
                                            ->when($component->hasMediaFilter(), fn(Collection $media): Collection => $component->filterMedia($media))
                                            ->each(fn(Media $media) => $media->delete());

                                        /** @var FileAdder $mediaAdder */
                                        $mediaAdder = $record->addMediaFromString($file->get());

                                        $filename = $component->getUploadedFileNameForStorage($file);

                                        $media = $mediaAdder
                                            ->addCustomHeaders($component->getCustomHeaders())
                                            ->usingFileName($filename)
                                            ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                            ->storingConversionsOnDisk($component->getConversionsDisk() ?? '')
                                            ->withCustomProperties($component->getCustomProperties())
                                            ->withManipulations($component->getManipulations())
                                            ->withResponsiveImagesIf($component->hasResponsiveImages())
                                            ->withProperties($component->getProperties())
                                            ->toMediaCollection($component->getCollection() ?? 'default', $component->getDiskName());

                                        $record->update(['value' => $media->getUrl()]);
                                        return $media->getAttributeValue('uuid');
                                    })
                                    ->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record): void {
                                        /** @var Model&HasMedia $record */
                                        $record = Setting::with('media')->where('key', '=', 'app_logo')->first();
                                        $media = $record->load('media')->getMedia($component->getCollection() ?? 'default')
                                            ->when(
                                                $component->hasMediaFilter(),
                                                fn(Collection $media) => $component->filterMedia($media)
                                            )
                                            ->when(
                                                ! $component->isMultiple(),
                                                fn(Collection $media): Collection => $media->take(1),
                                            )
                                            ->mapWithKeys(function (Media $media): array {
                                                $uuid = $media->getAttributeValue('uuid');
                                                return [$uuid => $uuid];
                                            })
                                            ->toArray();
                                        $component->state($media);
                                    })
                                    ->getUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, string $file): ?array {
                                        if (! $component->getRecord()) {
                                            return null;
                                        }
                                        $record = Setting::with('media')->where('key', '=', 'app_logo')->first();
                                        $media = $record->getRelationValue('media')->firstWhere('uuid', $file);

                                        $url = null;

                                        if ($component->getVisibility() === 'private') {
                                            $conversion = $component->getConversion();

                                            try {
                                                $url = $media?->getTemporaryUrl(
                                                    now()->addMinutes(5),
                                                    (filled($conversion) && $media->hasGeneratedConversion($conversion)) ? $conversion : '',
                                                );
                                            } catch (Throwable $exception) {
                                                // This driver does not support creating temporary URLs.
                                            }
                                        }

                                        if ($component->getConversion() && $media?->hasGeneratedConversion($component->getConversion())) {
                                            $url ??= $media->getUrl($component->getConversion());
                                        }

                                        $url ??= $media?->getUrl();

                                        return [
                                            'name' => $media?->getAttributeValue('name') ?? $media?->getAttributeValue('file_name'),
                                            'size' => $media?->getAttributeValue('size'),
                                            'type' => $media?->getAttributeValue('mime_type'),
                                            'url' => $url,
                                        ];
                                    })
                                    ->required(),
                                SpatieMediaLibraryFileUpload::make('favicon_icon')
                                    ->label(__('messages.setting.fav_icon') . ':')
                                    ->avatar()
                                    ->imageCropAspectRatio(null)
                                    ->imageEditor()
                                    ->image()
                                    ->disk(config('app.media_disk'))
                                    ->collection(Setting::PATH)
                                    ->required()
                                    ->validationAttribute(__('messages.setting.fav_icon'))
                                    ->saveUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, TemporaryUploadedFile $file, ?Model $record): ?string {
                                        $record = Setting::where('key', '=', 'favicon_icon')->first();
                                        if (! $record) {
                                            $record = Setting::create([
                                                'key' => 'favicon_icon',
                                                'value' => null,
                                            ]);
                                        }

                                        if (! method_exists($record, 'addMediaFromString')) {
                                            return $file;
                                        }

                                        try {
                                            if (! $file->exists()) {
                                                return null;
                                            }
                                        } catch (UnableToCheckFileExistence $exception) {
                                            return null;
                                        }

                                        $record->getMedia($component->getCollection() ?? 'default')
                                            ->whereNotIn('uuid', array_keys($component->getState() ?? []))
                                            ->when($component->hasMediaFilter(), fn(Collection $media): Collection => $component->filterMedia($media))
                                            ->each(fn(Media $media) => $media->delete());

                                        /** @var FileAdder $mediaAdder */
                                        $mediaAdder = $record->addMediaFromString($file->get());

                                        $filename = $component->getUploadedFileNameForStorage($file);

                                        $media = $mediaAdder
                                            ->addCustomHeaders($component->getCustomHeaders())
                                            ->usingFileName($filename)
                                            ->usingName($component->getMediaName($file) ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                                            ->storingConversionsOnDisk($component->getConversionsDisk() ?? '')
                                            ->withCustomProperties($component->getCustomProperties())
                                            ->withManipulations($component->getManipulations())
                                            ->withResponsiveImagesIf($component->hasResponsiveImages())
                                            ->withProperties($component->getProperties())
                                            ->toMediaCollection($component->getCollection() ?? 'default', $component->getDiskName());

                                        $record->update(['value' => $media->getUrl()]);
                                        return $media->getAttributeValue('uuid');
                                    })
                                    ->loadStateFromRelationshipsUsing(static function (SpatieMediaLibraryFileUpload $component, HasMedia $record): void {
                                        /** @var Model&HasMedia $record */
                                        $record = Setting::with('media')->where('key', '=', 'favicon_icon')->first();

                                        $media = $record->load('media')->getMedia($component->getCollection() ?? 'default')
                                            ->when(
                                                $component->hasMediaFilter(),
                                                fn(Collection $media) => $component->filterMedia($media)
                                            )
                                            ->when(
                                                ! $component->isMultiple(),
                                                fn(Collection $media): Collection => $media->take(1),
                                            )
                                            ->mapWithKeys(function (Media $media): array {
                                                $uuid = $media->getAttributeValue('uuid');
                                                return [$uuid => $uuid];
                                            })
                                            ->toArray();

                                        $component->state($media);
                                    })
                                    ->getUploadedFileUsing(static function (SpatieMediaLibraryFileUpload $component, string $file): ?array {
                                        if (! $component->getRecord()) {
                                            return null;
                                        }
                                        $record = Setting::with('media')->where('key', '=', 'favicon_icon')->first();
                                        $media = $record->getRelationValue('media')->firstWhere('uuid', $file);

                                        $url = null;

                                        if ($component->getVisibility() === 'private') {
                                            $conversion = $component->getConversion();

                                            try {
                                                $url = $media?->getTemporaryUrl(
                                                    now()->addMinutes(5),
                                                    (filled($conversion) && $media->hasGeneratedConversion($conversion)) ? $conversion : '',
                                                );
                                            } catch (Throwable $exception) {
                                                // This driver does not support creating temporary URLs.
                                            }
                                        }

                                        if ($component->getConversion() && $media?->hasGeneratedConversion($component->getConversion())) {
                                            $url ??= $media->getUrl($component->getConversion());
                                        }

                                        $url ??= $media?->getUrl();

                                        return [
                                            'name' => $media?->getAttributeValue('name') ?? $media?->getAttributeValue('file_name'),
                                            'size' => $media?->getAttributeValue('size'),
                                            'type' => $media?->getAttributeValue('mime_type'),
                                            'url' => $url,
                                        ];
                                    }),

                            ])->columns(3),
                        Group::make()
                            ->schema([
                                TextInput::make('vat_no_label')
                                    ->label(__('messages.setting.vat_no_label') . ':')
                                    ->placeholder(__('messages.setting.vat_no_label')),
                                Select::make('default_language')
                                    ->options(getUserLanguages())
                                    ->searchable()
                                    ->label(__('messages.setting.default_language') . ':')
                                    ->native(false),
                                Toggle::make('show_additional_address_in_invoice')
                                    ->label(__('messages.setting.show_additional_address') . ':')
                                    ->live()
                                    ->reactive()
                                    ->inline(false),
                            ])->columns(3),
                    ]),
            ])->statePath('data');
    }
    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('messages.common.save'))
                ->submit('save'),
            Action::make('cancel')
                ->color('primary')
                ->label(__('messages.common.cancel'))
                ->action('cancel')
                ->outlined(),

        ];
    }

    public function save()
    {
        $input = $this->form->getState();

        $valid = app(SettingRepository::class)->updateSetting($input);

        if ($valid) {
            return Notification::make()
                ->success()
                ->title(__('messages.flash.setting_updated_successfully'))
                ->send();
        }
    }

    public function cancel()
    {
        return $this->redirect(route('filament.admin.settings.pages.general'));
    }

    public function getTimeZone(): array
    {
        $timezoneArr = json_decode(file_get_contents(public_path('timezone/timezone.json')), true);
        $timezones = [];

        foreach ($timezoneArr as $utcData) {
            foreach ($utcData['utc'] as $item) {
                $timezones[$item] = $item;
            }
        }

        return $timezones;
    }
    public function optimizeCache()
    {
        Artisan::call('optimize:clear');

        return Notification::make()
            ->success()
            ->title(__('messages.flash.application_cache_cleared'))
            ->send();
    }
}
