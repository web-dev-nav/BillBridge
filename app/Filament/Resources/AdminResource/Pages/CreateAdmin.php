<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Exception;
use App\Models\Role;
use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use App\Filament\Resources\AdminResource;
use Filament\Resources\Pages\CreateRecord;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateAdmin extends CreateRecord
{
    protected static string $resource = AdminResource::class;

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
        try {
            DB::beginTransaction();

            if (isset($input['region_code'])) {
                $phoneUtil = PhoneNumberUtil::getInstance();
                $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($input['region_code']));
                $input['region_code'] = $countryCode;
            }

            $input['password'] = Hash::make($input['password']);
            $input['language'] = getDefaultLanguage();
            $user = User::create($input);
            $user->assignRole(Role::ROLE_ADMIN);

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
        return __('messages.flash.admin_created_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
