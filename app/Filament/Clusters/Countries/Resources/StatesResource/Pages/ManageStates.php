<?php

namespace App\Filament\Clusters\Countries\Resources\StatesResource\Pages;

use App\Filament\Clusters\Countries\Resources\StatesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageStates extends ManageRecords
{
    protected static string $resource = StatesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.common.new') . ' ' . __('messages.state.state'))
                ->modalWidth("md")
                ->successNotificationTitle(__('messages.flash.state_create'))
                ->createAnother(false),
        ];
    }
}
