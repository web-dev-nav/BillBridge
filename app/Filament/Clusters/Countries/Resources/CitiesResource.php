<?php

namespace App\Filament\Clusters\Countries\Resources;

use Filament\Forms;
use App\Models\City;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Countries;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Clusters\Countries\Resources\CitiesResource\Pages;
use App\Filament\Clusters\Countries\Resources\CitiesResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class CitiesResource extends Resource
{
    protected static ?string $model = City::class;
    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;
    protected static ?int $navigationSort = 3;
    protected static ?string $cluster = Countries::class;
    public static function getNavigationLabel(): string
    {
        return __('messages.city.cities');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('messages.common.name') . ':')
                    ->unique('cities', 'name', ignoreRecord: true)
                    ->validationAttribute(__('messages.common.name'))
                    ->placeholder(__('messages.common.name'))
                    ->columnSpanFull(),
                Select::make('state_id')
                    ->required()
                    ->label(__('messages.state.state_name') . ':')
                    ->options(State::all()->pluck('name', 'id'))
                    ->validationAttribute(__('messages.city.state_name'))
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.city.city_name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('state.name')
                    ->label(__('messages.city.state_name'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordAction(null)
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth("md")->iconButton()->successNotificationTitle(__('messages.flash.city_update')),
                Tables\Actions\DeleteAction::make()->iconButton()->successNotificationTitle(__('messages.flash.city_delete')),
            ])
            ->bulkActions([
                //
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCities::route('/'),
        ];
    }
}
