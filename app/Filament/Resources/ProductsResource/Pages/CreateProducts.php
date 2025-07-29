<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ProductsResource;

class CreateProducts extends CreateRecord
{
    protected static string $resource = ProductsResource::class;
    protected static bool $canCreateAnother = false;

    public function getTitle(): string
    {
        return __('messages.product.create_product');
    }
    
    protected function getActions(): array
    {
        return [
            Action::make('back')
                ->label(__('messages.common.back'))
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.flash.product_created_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
