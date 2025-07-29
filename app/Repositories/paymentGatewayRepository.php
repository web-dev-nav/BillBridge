<?php

namespace App\Repositories;

use Exception;
use App\Models\Setting;
use Illuminate\Support\Arr;
use Filament\Notifications\Notification;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class paymentGatewayRepository
{
    protected $fieldSearchable = [
        'stripe_key',
        'stripe_secret',
        'paypal_client_id',
        'paypal_secret',
        'razorpay_key',
        'razorpay_secret',
    ];

    protected $availableKeys = [
        'stripe_key',
        'stripe_secret',
        'paypal_client_id',
        'paypal_secret',
        'razorpay_key',
        'razorpay_secret',
        'stripe_enabled',
        'paypal_enabled',
        'razorpay_enabled',
        'paystack_enabled',
        'paystack_key',
        'paystack_secret',
        'mercadopago_key',
        'mercadopago_secret',
        'mercadopago_enabled',
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Setting::class;
    }

    public function store($input): bool
    {

        $input['stripe_enabled'] = $input['stripe_enabled'] ? 1 : 0;
        $input['paypal_enabled'] = $input['paypal_enabled'] ? 1 : 0;
        $input['razorpay_enabled'] = $input['razorpay_enabled'] ? 1 : 0;
        $input['paystack_enabled'] = $input['paystack_enabled'] ? 1 : 0;
        $input['mercadopago_enabled'] = $input['mercadopago_enabled'] ? 1 : 0;

        $valid =  $this->checkPaymentValidation($input);
        if ($valid !== null && !$valid) {
            return false;
        }
        $inputArray = Arr::only($input, $this->availableKeys);
        foreach ($inputArray as $key => $value) {
            $value = $value ?? '';
            $setting = Setting::where('key', '=', $key)->first();

            if (empty($setting)) {
                Setting::create([
                    'key' => $key,
                    'value' => $value,
                ]);
            } else {
                $setting->update([
                    'value' => $value,
                ]);
            }
        }

        return true;
    }

    public function edit(): array
    {
        foreach ($this->availableKeys as $key) {
            $setting = Setting::where('key', $key)->first();
            $data[$key] = isset($setting) ? $setting->value : null;
        }

        return $data;
    }

    private function checkPaymentValidation($input)
    {
        try {
            if (
                $input['stripe_enabled'] == 1 &&
                (empty($input['stripe_key']) || empty($input['stripe_secret']))
            ) {
                throw new Exception('Please fill up all value for stripe payment gateway.');
            }
            if (
                $input['paypal_enabled'] == 1 &&
                (empty($input['paypal_client_id']) || empty($input['paypal_secret']))
            ) {
                throw new Exception('Please fill up all value for paypal payment gateway.');
            }
            if (
                $input['razorpay_enabled'] == 1 &&
                (empty($input['razorpay_key']) || empty($input['razorpay_secret']))
            ) {
                throw new Exception('Please fill up all value for razorpay payment gateway.');
            }
            if (
                $input['paystack_enabled'] == 1 &&
                (empty($input['paystack_key']) || empty($input['paystack_secret']))
            ) {
                throw new Exception('Please fill up all value for paystack payment gateway.');
            }
            if (
                $input['mercadopago_enabled'] == 1 &&
                (empty($input['mercadopago_key']) || empty($input['mercadopago_secret']))
            ) {
                throw new Exception('Please fill up all value for mercadopago payment gateway.');
            }
            return true;
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
            return false;
        }
    }
}
