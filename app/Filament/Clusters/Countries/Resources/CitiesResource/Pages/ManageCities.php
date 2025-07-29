<?php

namespace App\Filament\Clusters\Countries\Resources\CitiesResource\Pages;

use App\Filament\Clusters\Countries\Resources\CitiesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCities extends ManageRecords
{
    protected static string $resource = CitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.common.new') . ' ' . __('messages.city.city'))
                ->modalWidth("md")
                ->successNotificationTitle(__('messages.flash.city_create'))
                ->createAnother(false),
        ];
    }
}
