<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ClientResource;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;

class ViewClient extends ViewRecord
{
    protected static string $resource = ClientResource::class;

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
                                if (!$record->user->hasMedia(User::PROFILE)) {
                                    return asset('images/avatar.png');
                                }
                            })->circular()->columnSpan(1),
                        Group::make([
                            TextEntry::make(''),
                            TextEntry::make('user.full_name')
                                ->size(15)
                                ->formatStateUsing(fn($record) => "<span class='font-bold'> {$record->user->full_name} </span> <a href='mailto:{$record->user->email}'>{$record->user->email}</a>")
                                ->html()
                                ->label(''),
                        ])->extraAttributes(['class' => 'display-block']),
                    ])->columns(10),
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('messages.quote.overview'))
                            ->schema([
                                TextEntry::make('user.full_name')
                                    ->label(__('messages.user.full_name') . ':'),
                                TextEntry::make('user.email')
                                    ->label(__('messages.user.email') . ':'),
                                PhoneEntry::make('user.contact')
                                    ->label(__('messages.client.contact_no') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('country.name')
                                    ->label(__('messages.client.country') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('state.name')
                                    ->label(__('messages.client.state') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('city.name')
                                    ->label(__('messages.client.city') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('address')
                                    ->label(__('messages.client.address') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('note')
                                    ->label(__('messages.client.notes') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('company_name')
                                    ->label(__('messages.setting.company_name') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('vat_no')
                                    ->label(__(getVatNoLabel())),
                                TextEntry::make('created_at')
                                    ->since()
                                    ->label(__('messages.common.created_at') . ':'),
                                TextEntry::make('updated_at')
                                    ->since()
                                    ->label(__('messages.common.updated_at') . ':'),

                            ])->columns(2)
                    ])->columnSpanFull()
            ]);
    }
}
