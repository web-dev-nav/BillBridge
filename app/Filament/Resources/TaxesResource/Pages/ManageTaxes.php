<?php

namespace App\Filament\Resources\TaxesResource\Pages;

use App\Models\Tax;
use Filament\Actions;
use App\Filament\Resources\TaxesResource;
use Filament\Resources\Pages\ManageRecords;

class ManageTaxes extends ManageRecords
{
    protected static string $resource = TaxesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.common.new') . ' ' . __('messages.tax.tax'))
                ->modalWidth("md")
                ->successNotificationTitle(__('messages.flash.tax_saved_successfully'))
                ->after(fn($record) => $record->is_default ? Tax::where('id', '!=', $record->id)->update(['is_default' => 0]) : $record->is_default)
                ->createAnother(false),
        ];
    }
}
