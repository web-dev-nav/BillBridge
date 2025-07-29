<?php

namespace App\Filament\Resources\ProductsResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use App\Filament\Resources\ProductsResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class ViewProducts extends ViewRecord
{
    protected static string $resource = ProductsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->url(static::getResource()::getUrl('index'))
                ->label(__('messages.common.back'))
                ->outlined(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('user.profile')->collection($this->getModel()::Image)->label("")->columnSpan(2)->width(100)->height(100)
                            ->defaultImageUrl(function ($record) {
                                if (!$record->hasMedia($this->getModel()::Image)) {
                                    return asset('images/avatar.png');
                                }
                            })->circular()->columnSpan(1),
                        Group::make([
                            TextEntry::make(''),
                            TextEntry::make('name')
                                ->size(15)
                                ->weight(FontWeight::Bold)
                                ->hiddenLabel(),
                        ])->extraAttributes(['class' => 'pt-3'])->columnSpan(2),
                    ])->columns(10),
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('messages.quote.overview'))
                            ->schema([
                                TextEntry::make('unit_price')
                                    ->default('N/A')
                                    ->label(__('messages.product.unit_price') . ':')
                                    ->state(fn($record) => getCurrencyAmount($record->unit_price, true)),
                                TextEntry::make('category.name')
                                    ->default('N/A')
                                    ->label(__('messages.product.category') . ':'),
                                TextEntry::make('code')
                                    ->default('N/A')
                                    ->label(__('messages.product.code') . ':'),
                                TextEntry::make('description')
                                    ->default('N/A')
                                    ->label(__('messages.product.description') . ':'),
                                TextEntry::make('updated_at')
                                    ->default('N/A')
                                    ->since()
                                    ->label(__('messages.common.last_update') . ':'),
                            ])->columns(2)
                    ])->columnSpanFull()
            ]);
    }
}
