<?php

namespace App\Filament\Resources\ClientResource\Pages;

use Exception;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Filament\Actions;
use App\Models\Client;
use App\Mail\CreateNewClientMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function beforeCreate(): void
    {
        if (isset($this->data['region_code'])) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($this->data['region_code']));
            $this->data['region_code'] = $countryCode;
        }

        $user = User::where('contact', $this->data['contact'])->where('region_code', $this->data['region_code'])->first();
        if ($user) {
            Notification::make()
                ->danger()
                ->title(__('messages.flash.contact_number_already_exists'))
                ->send();
            $this->halt();
        }
    }

    protected function handleRecordCreation(array $input): Model
    {
        if (!empty($input['city_name'])) {
            $city = City::where('name', $input['city_name'])->where('state_id', $input['state_id'])->first();
            if (!$city) {
                $city = City::create([
                    'name' => $input['city_name'],
                    'state_id' => $input['state_id'],
                ]);
            }
            $input['city_id'] = $city->id;
        }

        if (isset($input['region_code'])) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($input['region_code']));
            $input['region_code'] = $countryCode;
        }

        try {
            DB::beginTransaction();
            $input['client_password'] = $input['password'];
            $input['password'] = Hash::make($input['password']);
            $input['language'] = getDefaultLanguage();




            /** @var User $user */
            $user = User::create($input);
            $user->assignRole(Role::ROLE_CLIENT);

            $input['user_id'] = $user->id;
            $client = Client::create($input);

            if (getSettingValue('mail_notification')) {
                Mail::to($input['email'])->send(new CreateNewClientMail($input));
            }
            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
            $this->halt();
        }
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.flash.client_created_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
