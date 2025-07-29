<?php

namespace App\Filament\Clusters\Countries\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Countries;
use App\AdminDashboardSidebarSorting;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Countries\Resources\CountriesResource\Pages;
use App\Filament\Clusters\Countries\Resources\CountriesResource\RelationManagers;
use App\Models\State;
use Filament\Notifications\Notification;

class CountriesResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Countries::class;
    public static function getNavigationLabel(): string
    {
        return __('messages.country.countries');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('messages.common.name') . ':')
                    ->unique('countries', 'name', ignoreRecord: true)
                    ->validationAttribute(__('messages.common.name'))
                    ->placeholder(__('messages.common.name'))
                    ->columnSpanFull(),
                TextInput::make('short_code')
                    ->required()
                    ->label(__('messages.country.short_code') . ':')
                    ->unique('countries', 'short_code', ignoreRecord: true)
                    ->maxLength(2)
                    ->validationAttribute(__('messages.country.short_code'))
                    ->placeholder(__('messages.country.short_code'))
                    ->columnSpanFull(),
                TextInput::make('phone_code')
                    ->required()
                    ->label(__('messages.country.phone_code') . ':')
                    ->placeholder(__('messages.country.phone_code'))
                    ->unique('countries', 'phone_code', ignoreRecord: true)
                    ->maxLength(4)
                    ->mask('9999')
                    ->numeric()
                    ->validationAttribute(__('messages.country.phone_code'))
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('short_code')
                    ->label(__('messages.country.short_code'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone_code')
                    ->label(__('messages.country.phone_code'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordAction(null)
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth("md")->iconButton()->successNotificationTitle(__('messages.flash.country_update')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->action(function($record) {
                        $stateModels = [
                            State::class,
                        ];
                        $result = canDelete($stateModels, 'country_id', $record['id']);

                        if ($result) {
                            return Notification::make()
                                ->danger()
                                ->title(__('messages.flash.country_used'))
                                ->send();
                        }
                        $record->delete();

                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.country_delete'))
                            ->send();
                    }),
            ])
            ->bulkActions([
                //
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCountries::route('/'),
        ];
    }
}
