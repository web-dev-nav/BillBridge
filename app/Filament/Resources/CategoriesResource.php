<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\AdminDashboardSidebarSorting;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoriesResource\Pages;
use App\Filament\Resources\CategoriesResource\RelationManagers;
use Filament\Notifications\Notification;

class CategoriesResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-table-cells';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::CATEGORIES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.categories');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.categories');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label(__('messages.common.name') . ':')
                    ->validationAttribute(__('messages.common.name'))
                    ->unique('categories', 'name', ignoreRecord: true)
                    ->placeholder(__('messages.common.name'))
                    ->columnSpanFull()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('name')
                    ->label(__('messages.category.category'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label(__('messages.product.product'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordAction(null)
            ->actions([
                Tables\Actions\EditAction::make()->modalWidth("md")->iconButton()->successNotificationTitle(__('messages.flash.category_updated_successfully')),
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record) {
                        if ($record->products()->exists()) {
                            Notification::make()->danger()->title(__('messages.flash.category_cant_deleted'))->send();
                            return;
                        }
                        return $record->delete();
                    })
                    ->iconButton()->successNotificationTitle(__('messages.flash.category_deleted_successfully')),
            ])
            ->bulkActions([
                //
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
