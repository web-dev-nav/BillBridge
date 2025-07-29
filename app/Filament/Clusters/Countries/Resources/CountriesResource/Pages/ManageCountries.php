<?php

namespace App\Filament\Clusters\Countries\Resources\CountriesResource\Pages;

use App\Filament\Clusters\Countries\Resources\CountriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCountries extends ManageRecords
{
    protected static string $resource = CountriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.common.new') . ' ' . __('messages.country.country'))
                ->modalWidth("md")
                ->successNotificationTitle(__('messages.flash.country_create'))
                ->createAnother(false),
        ];
    }
}
