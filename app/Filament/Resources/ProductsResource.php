<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductsResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ProductsResource\RelationManagers;
use Filament\Forms\Components\Section;

class ProductsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::PRODUCTS->value;
    public static function getNavigationLabel(): string
    {
        return __('messages.products');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.products');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label(__('messages.common.name') . ':')
                            ->validationAttribute(__('messages.common.name'))
                            ->placeholder(__('messages.common.name'))
                            ->maxLength(255),
                        TextInput::make('code')
                            ->required()
                            ->default(strtoupper(Str::random(6)))
                            ->unique('products', 'code', ignoreRecord: true)
                            ->readOnly()
                            ->label(__('messages.product.code') . ':')
                            ->placeholder(__('messages.product.code'))
                            ->validationAttribute(__('messages.product.code')),
                        Select::make('category_id')
                            ->required()
                            ->options(Category::all()->pluck('name', 'id'))
                            ->native(false)
                            ->label(__('messages.product.category') . ':')
                            ->validationAttribute(__('messages.product.category')),
                        TextInput::make('unit_price')
                            ->required()
                            ->label(__('messages.product.unit_price') . ':')
                            ->placeholder(__('messages.product.unit_price'))
                            ->numeric()
                            ->minValue(1)
                            ->validationAttribute(__('messages.product.unit_price')),
                        Textarea::make('description')
                            ->label(__('messages.product.description') . ':')
                            ->placeholder(__('messages.product.description'))
                            ->rows(5),
                        SpatieMediaLibraryFileUpload::make('product')
                            ->label(__('messages.product.image') . ':')
                            ->disk(config('app.media_disk'))
                            ->collection(Product::Image),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('product')
                    ->label(__('messages.common.name'))
                    ->collection(Product::Image)
                    ->circular()
                    ->defaultImageUrl(asset('images/default-product.jpg'))
                    ->width(50)
                    ->height(50)
                    ->sortable(['name']),
                TextColumn::make('name')
                    ->label('')
                    ->html()
                    ->color('primary')
                    ->formatStateUsing(fn($record) => "<a href=" . self::getUrl('view', ['record' => $record]) . ">{$record->name}</a>")
                    ->description(function (Product $record) {
                        return $record->code;
                    })
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['name', 'code']),
                TextColumn::make('category.name')
                    ->label(__('messages.product.category'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('unit_price')
                    ->label(__('messages.product.price'))
                    ->formatStateUsing(fn($record) => getCurrencyAmount($record->unit_price, true))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->recordUrl(false)
            ->actions([
                Tables\Actions\ViewAction::make()->iconButton()->hidden(),
                Tables\Actions\EditAction::make()->iconButton()->successNotificationTitle(__('messages.flash.product_updated_successfully')),
                Tables\Actions\DeleteAction::make()->iconButton()->successNotificationTitle(__('messages.flash.product_deleted_successfully')),
            ])
            ->bulkActions([
                //
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
            'view' => Pages\ViewProducts::route('/{record}/view'),
        ];
    }
}
