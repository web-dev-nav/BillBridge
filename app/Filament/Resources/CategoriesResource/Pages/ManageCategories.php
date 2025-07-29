<?php

namespace App\Filament\Resources\CategoriesResource\Pages;

use App\Filament\Resources\CategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageCategories extends ManageRecords
{
    protected static string $resource = CategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('messages.common.new') . ' ' . __('messages.category.category'))
                ->modalWidth("md")
                ->successNotificationTitle(__('messages.flash.category_saved_successfully'))
                ->createAnother(false),
        ];
    }
}
