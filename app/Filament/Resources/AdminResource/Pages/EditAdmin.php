<?php

namespace App\Filament\Resources\AdminResource\Pages;

use Exception;
use App\Models\User;
use Filament\Actions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\AdminResource;
use Filament\Tables\Actions\DeleteAction;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function beforeSave(): void
    {
        if (isset($this->data['region_code'])) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($this->data['region_code']));
            $this->data['region_code'] = $countryCode;
        }

        $user = User::where('contact', $this->data['contact'])->where('region_code', $this->data['region_code'])->where('id', '!=', $this->data['id'])->first();
        if ($user) {
            Notification::make()
                ->danger()
                ->title(__('messages.flash.contact_number_already_exists'))
                ->send();
            $this->halt();
        }
    }

    protected function handleRecordUpdate(Model $record, array $input): Model
    {
        try {
            DB::beginTransaction();


            if (isset($input['region_code'])) {
                $phoneUtil = PhoneNumberUtil::getInstance();
                $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($input['region_code']));
                $input['region_code'] = $countryCode;
            }

            $user = User::find($record->id);

            if (! empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                unset($input['password']);
            }
            $user->update($input);

            if (isset($input['profile']) && ! empty($input['profile'])) {
                $user->clearMediaCollection(User::PROFILE);
                $user->media()->delete();
                $user->addMedia($input['profile'])->toMediaCollection(User::PROFILE, config('app.media_disc'));
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

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.flash.admin_updated_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
