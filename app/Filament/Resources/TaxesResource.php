<?php

namespace App\Filament\Resources;

use App\Models\Tax;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Filament\Forms\Components\Radio;
use App\AdminDashboardSidebarSorting;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TaxesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TaxesResource\RelationManagers;
use App\Models\InvoiceItemTax;
use Filament\Notifications\Notification;

class TaxesResource extends Resource
{
    protected static ?string $model = Tax::class;

    protected static ?string $navigationIcon = 'heroicon-o-percent-badge';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::TAXES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.taxes');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('messages.common.name') . ':')
                    ->validationAttribute(__('messages.common.name'))
                    ->placeholder(__('messages.common.name'))
                    ->columnSpanFull()
                    ->maxLength(255),
                TextInput::make('value')
                    ->required()
                    ->label(__('messages.common.value') . ':')
                    ->validationAttribute(__('messages.common.value'))
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(100)
                    ->placeholder(__('messages.common.value'))
                    ->columnSpanFull(),
                Radio::make('is_default')
                    ->label(__('messages.tax.is_default') . ':')
                    ->options([
                        '1' => __('messages.tax.yes'),
                        '0' => __('messages.tax.no'),
                    ])
                    ->required()
                    ->validationAttribute(__('messages.tax.is_default'))
                    ->default('0'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.common.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('value')
                    ->label(__('messages.common.value'))
                    ->sortable()
                    ->suffix(' %')
                    ->searchable(),
                ToggleColumn::make('is_default')
                    ->afterStateUpdated(function ($record) {
                        try {
                            Tax::where('id', '!=', $record->id)->update(['is_default' => 0]);
                            Notification::make()->success()->title(__('messages.flash.status_updated_successfully'))->send();
                        } catch (\Exception $e) {
                            return Notification::make()->danger()->title($e->getMessage())->send();
                        }
                    })
                    ->label(__('messages.common.default')),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordAction(null)
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->modalWidth("md")
                    ->after(fn($record) => $record->is_default ? Tax::where('id', '!=', $record->id)->update(['is_default' => 0]) : $record->is_default)
                    ->successNotificationTitle(__('messages.flash.tax_updated_successfully')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->action(function ($record) {
                        if ($record->is_default) {
                            return Notification::make()
                                ->danger()
                                ->title(__('messages.flash.tax_can_not_deleted'))
                                ->send();
                        }

                        $invoiceModels = [
                            InvoiceItemTax::class,
                        ];

                        $result = canDelete($invoiceModels, 'tax_id', $record['id']);

                        if ($result) {
                            return Notification::make()
                                ->danger()
                                ->title(__('messages.flash.tax_can_not_deleted'))
                                ->send();
                        }
                        $record->delete();

                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.tax_deleted_successfully'))
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
            'index' => Pages\ManageTaxes::route('/'),
        ];
    }
}
