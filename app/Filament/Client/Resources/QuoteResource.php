<?php

namespace App\Filament\Client\Resources;

use App\Models\Tax;
use App\Models\User;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Product;
use Filament\Forms\Form;
use Illuminate\Support\Arr;
use App\Models\InvoiceSetting;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Client\Resources\QuoteResource\Pages;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::QUOTES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.quotes');
    }
    public static function getModelLabel(): string
    {
        return __('messages.quote.quote');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Details')
                    ->schema([
                        Select::make('client_id')
                            ->label(__('messages.client.client') . ':')
                            ->validationAttribute(__('messages.client.client'))
                            ->afterStateHydrated(function ($record, $set, $operation) {
                                if ($operation === 'edit') {
                                    $set('client_id', $record->client->user->id);
                                }
                                if (auth()->user()->hasRole('client')) {
                                    $set('client_id', auth()->user()->id);
                                }
                            })
                            ->disabled(auth()->user()->hasRole('client'))
                            ->options(User::whereHas('client')->get()->pluck('full_name', 'id')->toArray())
                            ->preload()
                            ->optionsLimit(User::whereHas('client')->count())
                            ->native(false)
                            ->searchable()
                            ->required(),
                        TextInput::make('quote_id')
                            ->label(__('messages.quote.quote') . ' #')
                            ->validationAttribute(__('messages.quote.quote'))
                            ->disabled(auth()->user()->hasRole('client'))
                            ->default($form->model::generateUniqueQuoteId())
                            ->readOnly(),
                        DatePicker::make('quote_date')
                            ->required()
                            ->validationAttribute(__('messages.quote.quote_date'))
                            ->placeholder(__('messages.quote.quote_date'))
                            ->default(now())
                            ->live()
                            ->label(__('messages.quote.quote_date') . ':')
                            ->native(false),
                        DatePicker::make('due_date')
                            ->required()
                            ->validationAttribute(__('messages.quote.due_date'))
                            ->default(now()->addDays(1))
                            ->minDate(fn($get) => $get('quote_date') ?? now())
                            ->placeholder(__('messages.quote.due_date'))
                            ->label(__('messages.quote.due_date') . ':')
                            ->native(false),
                        Select::make('status')
                            ->required()
                            ->validationAttribute(__('messages.common.status'))
                            ->label(__('messages.common.status') . ':')
                            ->default(array_key_first(Arr::only(Quote::STATUS_ARR, Quote::DRAFT)))
                            ->options(getTranslatedData(Arr::only(Quote::STATUS_ARR, Quote::DRAFT)))
                            ->native(false),
                        Select::make('template_id')
                            ->required()
                            ->validationAttribute(__('messages.setting.invoice_template'))
                            ->label(__('messages.setting.invoice_template') . ':')
                            ->default(getInvoiceSettingTemplateId())
                            ->options(InvoiceSetting::toBase()->pluck('template_name', 'id')->toArray())
                            ->native(false)
                    ])
                    ->columns(3),
                Section::make('Product Details')
                    ->schema([
                        TextInput::make('product_details')
                            ->visible(false)
                            ->dehydrated(false),
                        Repeater::make('quoteItems')
                            ->label('')
                            ->schema([
                                Select::make('product_id')
                                    ->label(__('messages.product.product') . ':')
                                    ->validationAttribute(__('messages.product.product'))
                                    ->live()
                                    ->searchable()
                                    ->required()
                                    ->preload()
                                    ->native(false)
                                    ->options(function () {
                                        return Product::orderBy('name')->pluck('name', 'id')->toArray();
                                    })
                                    ->afterStateHydrated(function ($operation, $state, $set) {
                                        if ($operation == 'edit') {
                                            $set('product_name', $state);
                                        }
                                    })
                                    ->getSearchResultsUsing(static function ($component, ?string $search, $set): array {
                                        if (empty($search)) {
                                            return Product::all()->pluck('name', 'id')->orderBy('name')->toArray();
                                        }

                                        $searchResults = Product::query()
                                            ->when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
                                            ->orderBy('name')
                                            ->pluck('name', 'id')
                                            ->toArray();

                                        if (!in_array($search, $searchResults)) {
                                            $searchResults = array_merge([$search => $search], $searchResults);
                                            $set('product_name', $search);
                                        }

                                        return $searchResults;
                                    })
                                    ->optionsLimit(Product::all()->count() + 1)
                                    ->placeholder(__('messages.flash.select_product_or_enter_free_text')),
                                Hidden::make('product_name'),
                                TextInput::make('quantity')
                                    ->label(__('messages.invoice.qty') . ':')
                                    ->validationAttribute(__('messages.invoice.qty'))
                                    ->placeholder(__('messages.invoice.qty'))
                                    ->numeric()
                                    ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                                    ->required()
                                    ->minValue(0)
                                    ->reactive(),

                                TextInput::make('price')
                                    ->label(__('messages.product.unit_price') . ':')
                                    ->validationAttribute(__('messages.product.unit_price'))
                                    ->placeholder(__('messages.product.unit_price'))
                                    ->numeric()
                                    ->required()
                                    ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                                    ->minValue(0)
                                    ->reactive(),
                                Select::make('item_tax')
                                    ->label(__('messages.invoice.tax') . ':')
                                    ->validationAttribute(__('messages.invoice.tax'))
                                    ->multiple()
                                    ->options(Tax::all()->pluck('name', 'id')->toArray())
                                    ->default(fn() => empty(getDefaultTax()) ? [] : [getDefaultTax()])
                                    ->optionsLimit(Tax::all()->count())
                                    ->preload()
                                    ->searchable(),

                                Placeholder::make('amount')
                                    ->label(__('messages.quote.amount') . ' :')
                                    ->content(fn($get) => number_format(floatval($get('price') ?? 0) * floatval($get('quantity') ?? 1), 2))
                                    ->default('0.00'),
                            ])
                            ->live()
                            ->afterStateUpdated(function ($get, $set) {
                                self::calculateFinalAmount($get, $set);
                            })->columnSpanFull()
                            ->reorderable()
                            ->addActionLabel(__('messages.product.add_product'))
                            ->deletable(fn($state) => count($state) > 1)
                            ->columns(5),

                    ]),
                Section::make('Summary')
                    ->schema([
                        Group::make([
                            Placeholder::make('documentation')
                                ->hiddenLabel()
                                ->content(''),
                            Group::make([
                                Toggle::make('discount_before_tax')
                                    ->live()
                                    ->label(__('Discount %(applied before tax)'))
                                    ->afterStateUpdated(fn($get, $set) => self::calculateFinalAmount($get, $set))
                                    ->disabled(fn($get) => $get('discount_type') == Invoice::SELECT_DISCOUNT_TYPE || $get('discount_type') == Invoice::FIXED),
                                Group::make()
                                    ->schema([
                                        TextInput::make('discount')
                                            ->label(__('messages.invoice.discount') . ':')
                                            ->disabled(fn($get) => $get('discount_type') == Invoice::SELECT_DISCOUNT_TYPE)
                                            ->numeric()
                                            ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                                            ->live()
                                            ->afterStateUpdated(fn($set, $get) => $get('discount_type') == Invoice::PERCENTAGE && $get('discount') > 100 ? $set('discount', 0) : $set('discount', $get('discount')))
                                            ->afterStateUpdated(fn($get, $set) => self::calculateFinalAmount($get, $set))
                                            ->debounce(1000)
                                            ->placeholder(__('messages.invoice.discount')),

                                        Select::make('discount_type')
                                            ->live()
                                            ->default(0)
                                            ->label(__('messages.invoice.discount_type') . ':')
                                            ->validationAttribute(__('messages.invoice.discount_type'))
                                            ->afterStateUpdated(function ($set, $state, $get) {
                                                if ($get('discount_type') == Invoice::PERCENTAGE && $get('discount') > 100) {
                                                    $set('discount', 0);
                                                } else {
                                                    $set('discount', $get('discount'));
                                                }
                                                if ($get('discount_type') != Invoice::PERCENTAGE && $get('discount_before_tax') == true) {
                                                    $set('discount_before_tax', false);
                                                } else {
                                                    $set('discount_before_tax', $get('discount_before_tax'));
                                                }
                                            })
                                            ->afterStateUpdated(fn($get, $set) => self::calculateFinalAmount($get, $set))
                                            ->options(getTranslatedData(Invoice::DISCOUNT_TYPE))
                                            ->native(false),

                                        Select::make('taxes')
                                            ->label(__('messages.invoice.tax') . ':')
                                            ->validationAttribute(__('messages.invoice.tax'))
                                            ->multiple()
                                            ->live()
                                            ->afterStateHydrated(function ($component, $operation, $record) {
                                                if ($operation == 'edit') {
                                                    $component->state($record->qouteTaxes->pluck('id')->toArray());
                                                }
                                            })
                                            ->afterStateUpdated(fn($get, $set) => self::calculateFinalAmount($get, $set))
                                            ->options(fn() => Tax::all()->pluck('name', 'id')->toArray() ?? [])
                                            ->optionsLimit(Tax::all()->count())
                                            ->preload()
                                            ->searchable(),
                                    ])->columns(2)
                                    ->extraAttributes(['class' => 'space-x-4'])
                            ])->columns(1),
                            Group::make([
                                Placeholder::make('sub_total')
                                    ->label(__('messages.quote.sub_total') . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->content(fn($state) => getCurrencyAmount($state, true))
                                    ->default('0.00'),


                                Placeholder::make('total_discount')
                                    ->label(__('messages.quote.discount') . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->content(fn($state) => getCurrencyAmount($state, true))->default('0.00'),

                                Placeholder::make('total_tax')
                                    ->label(__('messages.invoice.tax')  . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->content(fn($state) => getCurrencyAmount($state, true))->default('0.00'),

                                Placeholder::make('final_amount')
                                    ->label(__('messages.quote.total') . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->default('0.00')
                                    ->content(fn($state) => getCurrencyAmount($state, true))
                            ]),
                        ])->columns(3)->columnSpanFull(),
                        Hidden::make('total_discount'),
                        Hidden::make('total_tax'),
                        Hidden::make('sub_total')->afterStateHydrated(fn($operation, $get, $set, $state) => $operation == 'edit' ? self::calculateFinalAmount($get, $set) : $set('sub_total', $state)),
                        Hidden::make('final_amount'),
                        Actions::make([
                            Action::make('add_note_term')
                                ->icon('heroicon-o-plus')
                                ->label(__('messages.quote.add_note_term'))
                                ->hidden(fn($get, $record) => $get('open_term') == true || !empty($record->note) || !empty($record->term))
                                ->action(function ($set) {
                                    $set('note', '');
                                    $set('term', '');
                                    $set('open_term', true);
                                })
                                ->color('primary'),

                            Action::make('remove_note_term')
                                ->icon('heroicon-o-minus')
                                ->label(__('messages.quote.remove_note_term'))
                                ->hidden(fn($get, $record) => (empty($get('note')) || empty($get('term'))) && !$get('open_term'))
                                ->action(function ($set, $get) {
                                    $set('note', '');
                                    $set('term', '');
                                    $set('open_term', false);
                                })
                                ->color('danger')
                        ])->columnSpanFull()->live(),
                        Group::make([
                            Textarea::make('note')
                                ->live()
                                ->visible(fn($get, $record) => $get('open_term') || !empty($record->note)) // Visible if open_term is true or there's content in note
                                ->label(__('messages.quote.note')),

                            Textarea::make('term')
                                ->live()
                                ->visible(fn($get, $record) => $get('open_term') || !empty($record->term)) // Visible if open_term is true or there's content in term
                                ->label(__('messages.quote.terms')),
                        ])->columns(2)->columnSpanFull()
                    ])

            ]);
    }

    public static function calculateFinalAmount($get, $set)
    {
        $itemWiseTaxes = 0;
        $totalAmount = 0;
        $previousItems = $get('product_details') ?? [];

        $quoteItems = collect($get('quoteItems'))->map(function ($item, $key) use ($set, &$itemWiseTaxes, &$totalAmount, $previousItems) {
            $productUpdated = isset($previousItems[$key]) && $previousItems[$key]['product_id'] != $item['product_id'];

            $product = Product::find($item['product_id']);
            $quantity = $productUpdated ? 1 : (int) ($item['quantity'] ?? 1);
            $item['quantity'] = $quantity;
            $price = $productUpdated ? (isset($product->unit_price) ? (float) $product->unit_price : 0) : (isset($item['price']) ? (float) $item['price'] : (isset($product->unit_price) ? (float) $product->unit_price : 0));
            $item['price'] = $price;

            $itemTotal = $quantity * $price;
            $totalAmount += $itemTotal;

            if (!empty($item['item_tax'])) {
                foreach ($item['item_tax'] as $taxId) {
                    $tax = Tax::find($taxId);
                    if ($tax) {
                        $itemWiseTaxes += ($itemTotal * $tax->value) / 100;
                    }
                }
            }

            return array_merge($item, ['amount' => number_format($itemTotal, 2, '.', '')]);
        })->toArray();

        $set('product_details', $quoteItems);
        $set('quoteItems', $quoteItems);

        $subTotal = collect($quoteItems)->sum(fn($item) => (float) $item['amount']);
        $set('sub_total', number_format($subTotal, 2, '.', ''));

        $totalTax = 0;
        if (!empty($get('taxes'))) {
            foreach ($get('taxes') as $taxId) {
                $tax = Tax::find($taxId);
                $totalTax += $tax->value;
            }
        }

        $finalAmountWithTax = $subTotal + $itemWiseTaxes;
        $totalTaxAmount = $finalAmountWithTax * ($totalTax / 100);
        $fianalTaxAmount = $itemWiseTaxes + $totalTaxAmount;

        $set('total_tax', number_format($fianalTaxAmount, 2, '.', ''));

        $finalAmount = $finalAmountWithTax + $totalTaxAmount;

        $discountType = $get('discount_type');
        $discount = (float) $get('discount');
        $discountAmount = 0;
        $isDiscountBeforeTax = $get('discount_before_tax');

        if ($discountType == Quote::FIXED) {
            $discountAmount = $discount;
        } elseif ($discountType == Quote::PERCENTAGE) {
            if ($isDiscountBeforeTax) {
                $discountAmount = ($subTotal * $discount) / 100;
                $finalAmountWithTax -= $discountAmount;
                $finalAmount = $finalAmountWithTax + ($finalAmountWithTax * ($totalTax / 100));
            } else {
                $finalAmountWithTax = $finalAmountWithTax +  $totalTaxAmount;
                $discountAmount = ($finalAmountWithTax * $discount) / 100;
                $finalAmount -= $discountAmount;
            }
        }

        $set('total_discount', number_format($discountAmount, 2, '.', ''));
        $set('final_amount', number_format($finalAmount, 2, '.', ''));

        return $finalAmount;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotes::route('/'),
            'create' => Pages\CreateQuote::route('/create'),
            'view' => Pages\ViewQuote::route('/{record}'),
            'edit' => Pages\EditQuote::route('/{record}/edit'),
        ];
    }
}
