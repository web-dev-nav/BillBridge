<?php

namespace App\Filament\Clusters\Settings\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use App\Filament\Clusters\Settings;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\Settings\Resources\CurrencyResource\Pages;
use App\Filament\Clusters\Settings\Resources\CurrencyResource\RelationManagers;

class CurrencyResource extends Resource
{
    protected static ?string $model = Currency::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Settings::class;

    public static function getLabel(): ?string
    {
        return __('messages.currency.currency');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('messages.common.name') . ':')
                    ->unique(ignoreRecord: true)
                    ->validationAttribute(__('messages.common.name'))
                    ->required()
                    ->placeholder(__('messages.common.name'))
                    ->maxLength(255),
                Forms\Components\TextInput::make('icon')
                    ->label(__('messages.currency.icon') . ':')
                    ->validationAttribute(__('messages.currency.icon'))
                    ->placeholder(__('messages.currency.icon'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('code')
                    ->label(__('messages.currency.currency_code') . ':')
                    ->required()
                    ->placeholder(__('messages.currency.currency_code'))
                    ->maxLength(255),

                Placeholder::make('documentation')
                    ->label('')
                    ->content(new HtmlString('<b>Note</b> : Add currency code as per three-letter ISO code.<a href="https://docs.stripe.com/currencies" target="_blank"> <span style="color:#6571FF;text-decoration:underline">' . __("messages.currency.you_can_find_out_here") . '</span></a>'))

            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label(__('messages.currency.icon'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('messages.currency.currency_code'))
                    ->sortable()
                    ->searchable(),

            ])
            ->recordUrl(false)
            ->recordAction(false)
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->tooltip(__('messages.common.edit'))->modalWidth('md')->successNotificationTitle(__('messages.flash.currency_updated_successfully')),
                Tables\Actions\DeleteAction::make()->iconButton()->tooltip(__('messages.common.delete'))->successNotificationTitle(__('messages.flash.currency_deleted_successfully'))
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCurrencies::route('/'),
        ];
    }
}
