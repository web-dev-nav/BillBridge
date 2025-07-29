<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Models\City;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ClientResource;
use App\Repositories\ClientRepository;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberUtil;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EditClient extends EditRecord
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $data + User::where('id', $data['user_id'])->first()->toArray();
    }

    protected function beforeSave(): void
    {
        if (isset($this->data['region_code'])) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($this->data['region_code']));
            $this->data['region_code'] = $countryCode;
        }

        $user = User::where('contact', $this->data['contact'])->where('region_code', $this->data['region_code'])->where('id', '!=', $this->data['user_id'])->first();
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

        $record->load('user');



        if (isset($input['region_code'])) {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $countryCode = $phoneUtil->getCountryCodeForRegion(strtoupper($input['region_code']));
            $input['region_code'] = $countryCode;
        }
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


        try {
            $clientRepository = app(ClientRepository::class);
            $client = $clientRepository->updateClient($input, $record);

            return $client;
        } catch (Exception $e) {
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
            $this->halt();
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.flash.client_updated_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
