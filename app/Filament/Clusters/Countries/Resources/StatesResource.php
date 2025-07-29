<?php

namespace App\Filament\Clusters\Countries\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use App\Models\Country;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Countries;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Countries\Resources\StatesResource\Pages;
use App\Filament\Clusters\Countries\Resources\StatesResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class StatesResource extends Resource
{
    protected static ?string $model = State::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 2;
    protected static ?string $cluster = Countries::class;
    public static function getNavigationLabel(): string
    {
        return __('messages.state.states');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('messages.common.name') . ':')
                    ->unique('states', 'name', ignoreRecord: true)
                    ->validationAttribute(__('messages.common.name'))
                    ->placeholder(__('messages.common.name'))
                    ->columnSpanFull(),
                Select::make('country_id')
                    ->required()
                    ->label(__('messages.state.country_name') . ':')
                    ->options(Country::all()->pluck('name', 'id'))
                    ->validationAttribute(__('messages.state.country_name'))
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.state.state_name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('country.name')
                    ->label(__('messages.state.country_name'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordAction(null)
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->modalWidth("md")->successNotificationTitle(__('messages.flash.state_update')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->action(function ($record) {
                        $cityModels = [
                            City::class,
                        ];
                        $result = canDelete($cityModels, 'state_id', $record['id']);

                        if ($result) {
                            return Notification::make()
                                ->danger()
                                ->title(__('messages.flash.state_used'))
                                ->send();
                        }
                        $record->delete();

                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.state_delete'))
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
            'index' => Pages\ManageStates::route('/'),
        ];
    }
}
