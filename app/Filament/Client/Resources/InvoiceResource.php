<?php

namespace App\Filament\Client\Resources;

use App\Models\Tax;
use Filament\Forms;
use App\Models\User;
use App\Models\Quote;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Currency;
use Filament\Forms\Form;
use App\Models\PaymentQrCode;
use App\Models\InvoiceItemTax;
use App\Models\InvoiceSetting;
use Filament\Resources\Resource;
use Filament\Forms\Components\Group;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Actions\Action;
use Livewire\Features\SupportEvents\HandlesEvents;
use App\Filament\Client\Resources\InvoiceResource\Pages;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::INVOICES->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.invoices');
    }

    public static function getModelLabel(): string
    {
        return __('messages.invoice.invoice');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Basic Details')
                    ->schema([
                        Select::make('client_id')
                            ->label(__('messages.invoice.client') . ':')
                            ->validationAttribute(__('messages.invoice.client'))
                            ->afterStateHydrated(function ($record, $set, $operation) {
                                if ($operation === 'edit') {
                                    $set('client_id', $record->client->user->id);
                                }
                            })
                            ->options(User::whereHas('client')->get()->pluck('full_name', 'id')->toArray())
                            ->preload()
                            ->optionsLimit(User::whereHas('client')->count())
                            ->native(false)
                            ->searchable()
                            ->required(),

                        TextInput::make('invoice_id')
                            ->label(__('messages.invoice.invoice_number') . ':')
                            ->validationAttribute(__('messages.invoice.invoice_number'))
                            ->default(self::getModel()::generateUniqueInvoiceId())
                            ->disabledOn('edit')
                            ->extraInputAttributes([
                                'oninput' => "if (/[^a-zA-Z0-9]/.test(this.value)) {
                                                this.value = '" . self::getModel()::generateUniqueInvoiceId() . "';
                                            } else {
                                        this.value = this.value.toUpperCase();
                                    }"
                            ])
                            ->required()
                            ->rules(fn($operation) => $operation === 'edit' ? [] : ['regex:/^[a-zA-Z0-9]+$/'])
                            ->maxLength(fn($operation) => $operation === 'edit' ? null : 6)
                            ->prefix(fn($operation) => $operation != 'edit' ? (getInvoiceNoPrefix() ?: null) : null)
                            ->suffix(fn($operation) => $operation != 'edit' ? (getInvoiceNoSuffix() ?: null) : null),

                        DatePicker::make('invoice_date')
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
                            ->placeholder(__('messages.quote.due_date'))
                            ->label(__('messages.quote.due_date') . ':')
                            ->native(false),

                        Select::make('status')
                            ->required()
                            ->validationAttribute(__('messages.common.status'))
                            ->label(__('messages.common.status') . ':')
                            ->default(Invoice::UNPAID)
                            ->options(getTranslatedData(Invoice::STATUS_ARR))
                            ->native(false),

                        Select::make('template_id')
                            ->label(__('messages.setting.invoice_template') . ':')
                            ->required()
                            ->searchable()
                            ->native(false)
                            ->live()
                            ->default(getInvoiceSettingTemplateId())
                            ->validationAttribute(__('messages.setting.invoice_template'))
                            ->options(InvoiceSetting::toBase()->pluck('template_name', 'id')->toArray()),

                        Select::make('payment_qr_code_id')
                            ->label(__('messages.payment_qr_codes.payment_qr_code') . ':')
                            ->native(false)
                            ->validationAttribute(__('messages.payment_qr_codes.payment_qr_code'))
                            ->default(PaymentQrCode::whereIsDefault(true)->value('id') ?? null)
                            ->options(PaymentQrCode::pluck('title', 'id')->toArray() ?? null),

                        Select::make('currency_id')
                            ->label(__('messages.setting.currencies') . ':')
                            ->native(false)
                            ->optionsLimit(Currency::all()->count())
                            ->searchable()
                            ->options(Currency::all()->mapWithKeys(function ($currency) {
                                return [$currency->id => $currency->icon . ' ' . $currency->name];
                            })),

                        Toggle::make('recurring_status')
                            ->live()
                            ->label(__('messages.invoice.this_is_recurring_invoice')),
                        TextInput::make('recurring_cycle')
                            ->label(__('messages.invoice.recurring_cycle') . ':')
                            ->validationAttribute(__('messages.invoice.recurring_cycle'))
                            ->numeric()
                            ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                            ->hidden(fn($get) => !$get('recurring_status'))
                            ->placeholder(__('messages.flash.number_of_days_for_recurring_cycle')),
                    ])->columns(3),
                Section::make('Product Details')
                    ->schema([
                        TextInput::make('product_details')
                            ->visible(false)
                            ->dehydrated(false),
                        Forms\Components\Repeater::make('invoiceItems')
                            ->label('')
                            ->relationship('invoiceItems')
                            ->saveRelationshipsUsing(static function (Repeater $component, HasForms $livewire, ?array $state) {
                                if (!is_array($state)) {
                                    $state = [];
                                }

                                $relationship = $component->getRelationship();
                                $existingRecords = $component->getCachedExistingRecords();
                                $recordsToDelete = [];

                                foreach ($existingRecords->pluck($relationship->getRelated()->getKeyName()) as $keyToCheckForDeletion) {
                                    if (array_key_exists("record-{$keyToCheckForDeletion}", $state)) {
                                        continue;
                                    }

                                    $recordsToDelete[] = $keyToCheckForDeletion;
                                    $existingRecords->forget("record-{$keyToCheckForDeletion}");
                                }

                                $relationship
                                    ->whereKey($recordsToDelete)
                                    ->get()
                                    ->each(static fn(Model $record) => $record->delete());

                                $childComponentContainers = $component->getChildComponentContainers(
                                    withHidden: $component->shouldSaveRelationshipsWhenHidden(),
                                );

                                $itemOrder = 1;
                                $orderColumn = $component->getOrderColumn();
                                $translatableContentDriver = $livewire->makeFilamentTranslatableContentDriver();

                                foreach ($childComponentContainers as $itemKey => $item) {

                                    $itemData = $item->getState(shouldCallHooksBefore: false);

                                    $isProductExist = Product::where('id', $itemData['product_id'])->exists();

                                    if ($isProductExist) {
                                        $itemData['product_name'] = null;
                                    } else {
                                        $itemData['product_id'] = null;
                                    }

                                    if ($orderColumn) {
                                        $itemData[$orderColumn] = $itemOrder;
                                        $itemOrder++;
                                    }

                                    $itemData['total'] = floatval($itemData['price'] ?? 0) * floatval($itemData['quantity'] ?? 1);

                                    if ($record = ($existingRecords[$itemKey] ?? null)) {
                                        $itemData = $component->mutateRelationshipDataBeforeSave($itemData, record: $record);

                                        if ($itemData === null) {
                                            continue;
                                        }
                                        $translatableContentDriver
                                            ? $translatableContentDriver->updateRecord($record, $itemData)
                                            : $record->fill($itemData)->save();

                                        InvoiceItemTax::where('invoice_item_id', $record->id)->delete();

                                        if (!empty($itemData['tax_id'])) {
                                            foreach ($itemData['tax_id'] as $taxId) {
                                                $taxValue = Tax::where('id', $taxId)->value('value');

                                                InvoiceItemTax::create([
                                                    'invoice_item_id' => $record->id,
                                                    'tax_id' => $taxId,
                                                    'tax' => $taxValue ?? 0,
                                                ]);
                                            }
                                        }

                                        continue;
                                    }

                                    $relatedModel = $component->getRelatedModel();
                                    $itemData = $component->mutateRelationshipDataBeforeCreate($itemData);

                                    if ($itemData === null) {
                                        continue;
                                    }

                                    if ($translatableContentDriver) {
                                        $record = $translatableContentDriver->makeRecord($relatedModel, $itemData);
                                    } else {
                                        $record = new $relatedModel;
                                        $record->fill($itemData);
                                    }

                                    $record = $relationship->save($record);
                                    $item->model($record)->saveRelationships();
                                    $existingRecords->push($record);

                                    InvoiceItemTax::where('invoice_item_id', $record->id)->delete();

                                    if (!empty($itemData['tax_id'])) {
                                        foreach ($itemData['tax_id'] as $taxId) {
                                            $taxValue = Tax::where('id', $taxId)->value('value');

                                            InvoiceItemTax::create([
                                                'invoice_item_id' => $record->id,
                                                'tax_id' => $taxId,
                                                'tax' => $taxValue ?? 0,
                                            ]);
                                        }
                                    }
                                }

                                $component->getRecord()->setRelation($component->getRelationshipName(), $existingRecords);
                            })
                            ->mutateRelationshipDataBeforeFillUsing(function (array $data) {
                                if (isset($data['id'])) {
                                    $data['tax_id'] = InvoiceItemTax::where('invoice_item_id', $data['id'])->pluck('tax_id')->toArray();
                                }
                                return $data;
                            })
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
                                    ->afterStateHydrated(function ($operation, $state, $set, $record) {
                                        if ($operation == 'edit' && !empty($record->product_name)) {
                                            $set('product_id', $record->product_name);
                                        }
                                    })
                                    ->getSearchResultsUsing(static function ($component, ?string $search, $set): array {
                                        if (empty($search)) {
                                            return Product::all()->pluck('name', 'id')->orderBy('name')
                                                ->toArray();
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
                                Forms\Components\TextInput::make('quantity')
                                    ->label(__('messages.invoice.qty') . ':')
                                    ->validationAttribute(__('messages.invoice.qty'))
                                    ->placeholder(__('messages.invoice.qty'))
                                    ->numeric()
                                    ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                                    ->required()
                                    ->minValue(0)
                                    ->reactive(),

                                Forms\Components\TextInput::make('price')
                                    ->label(__('messages.product.unit_price') . ':')
                                    ->validationAttribute(__('messages.product.unit_price'))
                                    ->placeholder(__('messages.product.unit_price'))
                                    ->numeric()
                                    ->required()
                                    ->extraInputAttributes(['oninput' => "this.value = this.value.replace(/[e\+\-]/gi, '')"])
                                    ->minValue(0)
                                    ->reactive(),
                                Forms\Components\Select::make('tax_id')
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
                            })
                            ->columnSpanFull()
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
                                            ->readOnly(fn($get) => $get('discount_type') == Invoice::SELECT_DISCOUNT_TYPE)
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

                                        Forms\Components\Select::make('tax2')
                                            ->label(__('messages.invoice.tax') . ':')
                                            ->validationAttribute(__('messages.invoice.tax'))
                                            ->multiple()
                                            ->live()
                                            ->afterStateHydrated(function ($component, $operation, $record) {
                                                if ($operation == 'edit') {
                                                    $component->state($record->invoiceTaxes->pluck('id')->toArray());
                                                }
                                            })
                                            ->afterStateUpdated(fn($get, $set) => self::calculateFinalAmount($get, $set))
                                            ->options(fn() => Tax::all()->pluck('name', 'id')->toArray())
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
                                    ->content(function ($state, $livewire) {
                                        $currencyId = $livewire->getRecord()->currency_id ?? null;
                                        return getInvoiceCurrencyAmount($state, $currencyId, true);
                                    })
                                    ->default('0.00'),


                                Placeholder::make('total_discount')
                                    ->label(__('messages.quote.discount') . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->content(function ($state, $livewire) {
                                        $currencyId = $livewire->getRecord()->currency_id ?? null;
                                        return getInvoiceCurrencyAmount($state, $currencyId, true);
                                    })
                                    ->default('0.00'),

                                Placeholder::make('total_tax')
                                    ->label(__('messages.invoice.tax')  . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->content(function ($state, $livewire) {
                                        $currencyId = $livewire->getRecord()->currency_id ?? null;
                                        return getInvoiceCurrencyAmount($state, $currencyId, true);
                                    })
                                    ->default('0.00'),

                                Placeholder::make('total')
                                    ->label(__('messages.quote.total') . ' :')
                                    ->inlineLabel()
                                    ->extraAttributes(['class' => 'ms-auto'])
                                    ->default('0.00')
                                    ->content(function ($state, $livewire) {
                                        $currencyId = $livewire->getRecord()->currency_id ?? null;
                                        return getInvoiceCurrencyAmount($state, $currencyId, true);
                                    })
                            ]),
                        ])->columns(3)->columnSpanFull(),
                        Hidden::make('total_discount'),
                        Hidden::make('total_tax'),
                        Hidden::make('sub_total')->afterStateHydrated(fn($operation, $get, $set, $state) => $operation == 'edit' ? self::calculateFinalAmount($get, $set) : $set('sub_total', $state)),
                        Hidden::make('total'),
                        Actions::make([
                            Action::make('add_note_term')
                                ->icon('heroicon-o-plus')
                                ->label(__('messages.quote.add_note_term'))
                                ->hidden(fn($get) => $get('open_term') || !empty($get('note')) || !empty($get('term')))
                                ->action(function ($set) {
                                    $set('note', '');
                                    $set('term', '');
                                    $set('open_term', true);
                                })
                                ->color('primary'),

                            Action::make('remove_note_term')
                                ->icon('heroicon-o-minus')
                                ->label(__('messages.quote.remove_note_term'))
                                ->hidden(fn($get) => (!$get('open_term') && empty($get('note')) && empty($get('term'))))
                                ->action(function ($set) {
                                    $set('note', '');
                                    $set('term', '');
                                    $set('open_term', false);
                                })
                                ->color('danger')
                        ])->columnSpanFull()->live(),

                        Group::make([
                            Textarea::make('note')
                                ->live()
                                ->placeholder(__('messages.quote.note'))
                                // ->visible(fn($get) => $get('open_term') || !empty($get('note')))
                                ->label(__('messages.quote.note') . ':'),

                            Textarea::make('term')
                                ->live()
                                ->placeholder(__('messages.quote.terms'))
                                // ->visible(fn($get) => $get('open_term') || !empty($get('term')))
                                ->label(__('messages.quote.terms') . ':'),
                        ])->columns(2)->columnSpanFull()->visible(fn($get) => $get('open_term') || !empty($get('note')) || !empty($get('term'))),
                    ])
            ]);
    }

    public static function calculateFinalAmount($get, $set)
    {
        $itemWiseTaxes = 0;
        $totalAmount = 0;
        $previousItems = $get('product_details') ?? [];

        $invoiceItems = collect($get('invoiceItems'))->map(function ($item, $key) use ($set, &$itemWiseTaxes, &$totalAmount, $previousItems) {
            $productUpdated = isset($previousItems[$key]) && $previousItems[$key]['product_id'] != $item['product_id'];

            $product = Product::find($item['product_id']);
            $quantity = $productUpdated ? 1 : (int) ($item['quantity'] ?? 1);
            $item['quantity'] = $quantity;
            $price = $productUpdated ? (isset($product->unit_price) ? (float) $product->unit_price : 0) : (isset($item['price']) ? (float) $item['price'] : (isset($product->unit_price) ? (float) $product->unit_price : 0));
            $item['price'] = $price;

            $itemTotal = $quantity * $price;
            $totalAmount += $itemTotal;

            if (!empty($item['tax_id'])) {
                foreach ($item['tax_id'] as $taxId) {
                    $tax = Tax::find($taxId);
                    if ($tax) {
                        $itemWiseTaxes += ($itemTotal * $tax->value) / 100;
                    }
                }
            }

            return array_merge($item, ['amount' => number_format($itemTotal, 2, '.', '')]);
        })->toArray();

        $set('product_details', $invoiceItems);

        $set('invoiceItems', $invoiceItems);

        $subTotal = collect($invoiceItems)->sum(fn($item) => (float) $item['amount']);
        $set('sub_total', number_format($subTotal, 2, '.', ''));

        $totalTax = 0;
        if (!empty($get('tax2'))) {
            foreach ($get('tax2') as $taxId) {
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
        $set('total', number_format($finalAmount, 2, '.', ''));

        return $finalAmount;
    }


    public static function getTaxRate($taxId)
    {
        return \App\Models\Tax::where('id', $taxId)->value('value') ?? 0;
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
            'payment' => Pages\InvoicePaymentPage::route('/{record}/payment'),
        ];
    }
}
