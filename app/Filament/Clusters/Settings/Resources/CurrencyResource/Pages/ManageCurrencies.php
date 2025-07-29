<?php

namespace App\Filament\Clusters\Settings\Resources\CurrencyResource\Pages;

use App\Filament\Clusters\Settings\Resources\CurrencyResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCurrencies extends ManageRecords
{
    protected static string $resource = CurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->modalWidth("md")->createAnother(false)->successNotificationTitle(__('messages.flash.currency_saved_successfully'))->label( __('messages.currency.add_currency')),
        ];
    }


}
