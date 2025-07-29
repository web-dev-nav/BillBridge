<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\AdminResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;

class ViewAdmin extends ViewRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        SpatieMediaLibraryImageEntry::make('user.profile')->collection(User::PROFILE)->label("")->columnSpan(2)->width(100)->height(100)
                            ->defaultImageUrl(function ($record) {
                                if (!$record->hasMedia(User::PROFILE)) {
                                    return asset('images/avatar.png');
                                }
                            })->circular()->columnSpan(1),
                        Group::make([
                            TextEntry::make(''),
                            TextEntry::make('full_name')
                                ->size(15)
                                ->formatStateUsing(fn($record) => "<span class='font-bold'> {$record->full_name} </span> <a href='mailto:{$record->email}'>{$record->email}</a>")
                                ->html()
                                ->label(''),
                        ])->extraAttributes(['class' => 'display-block']),
                    ])->columns(10),
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('messages.quote.overview'))
                            ->schema([
                                TextEntry::make('contact')
                                    ->label(__('messages.user.contact_number')),
                                TextEntry::make('created_at')
                                    ->label(__('messages.registered_date'))
                            ])->columns(2)
                    ])->columnSpanFull()
            ]);
    }
}
