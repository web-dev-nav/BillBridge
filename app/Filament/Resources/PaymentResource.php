<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use App\Models\User;
use Filament\Tables;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\AdminPayment;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Tables\Filters\Filter;
use Illuminate\Contracts\View\View;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PaymentResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::PAYMENTS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.payments');
    }

    public static function getPluralLabel(): string
    {
        return __('messages.payments');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)->schema([
                    Select::make('invoice_id')
                        ->label(__('messages.invoice.invoice') . ':')
                        ->placeholder(__('messages.invoice.invoice'))
                        ->validationAttribute(__('messages.invoice.invoice'))
                        ->options(
                            Invoice::orderby('id', 'desc')->get()->pluck('invoice_id', 'id')
                        )
                        ->optionsLimit(Invoice::orderby('id', 'desc')->get()->count())
                        ->native(false)
                        ->live()
                        ->afterStateUpdated(function ($state, $get, $set) {
                            $set('invoice_id', $state);
                            self::getInvoiceAmount($get, $set);
                        })
                        ->searchable()
                        ->required(),
                    TextInput::make('due_amount')
                        ->label(__('messages.quote.due_amount') . ':')
                        ->disabled()
                        ->afterStateHydrated(function ($get, $set) {
                            $set('invoice_id', $get('invoice_id'));
                            self::getInvoiceAmount($get, $set);
                        })
                        ->suffix(fn($get) => self::CurrencyIcon($get('invoice_id')))
                        ->dehydrated(),
                    TextInput::make('paid_amount')
                        ->label(__('messages.quote.paid_amount') . ':')
                        ->disabled()
                        ->afterStateHydrated(function ($get, $set) {
                            $set('invoice_id', $get('invoice_id'));
                            self::getInvoiceAmount($get, $set);
                        })
                        ->suffix(function ($get) {
                            return self::CurrencyIcon($get('invoice_id'));
                        }),
                ])->columns(3),
                Grid::make(4)->schema([
                    DatePicker::make('payment_date')
                        ->label(__('messages.payment.payment_date') . ':')
                        ->default(Carbon::today())
                        ->validationAttribute(__('messages.payment.payment_date'))
                        ->required(),
                    TextInput::make('amount')
                        ->label(__('messages.quote.amount') . ':')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->suffix(function ($get) {
                            return self::CurrencyIcon($get('invoice_id'));
                        }),
                    TextInput::make('payment_mode')
                        ->label(__('messages.quote.payment_method') . ':')
                        ->default(Payment::PAYMENT_MODE[4])
                        ->afterStateHydrated(function ($component, $state) {
                            return $component->state(Payment::PAYMENT_MODE[$state] ?? Payment::PAYMENT_MODE[4]);
                        })
                        ->disabled(),
                ])->columns(3),
                Textarea::make('notes')
                    ->label(__('messages.quote.note') . ':')
                    ->validationAttribute(__('messages.quote.note'))
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),
            ]);
    }

    public static function getInvoiceAmount($get, $set)
    {
        if (!empty($get('invoice_id'))) {
            $invoice = Invoice::find($get('invoice_id'));
            $paidAmount = $invoice->payments()->where('is_approved', Payment::APPROVED)->sum('amount');
            $dueAmount = $invoice->final_amount - $paidAmount;

            $set('due_amount', $dueAmount);
            $set('paid_amount', $paidAmount);
        }
    }

    public static function CurrencyIcon($invoiceId)
    {
        $currencyId = getSettingValue('current_currency');
        if (!empty($currencyId)) {
            $invoice = Invoice::find($invoiceId);
            if ($invoice) {
                $currencyId = $invoice->currency_id;
            }
        }

        $invoiceCurrencyCode = Currency::whereId($currencyId)->first();

        return $invoiceCurrencyCode->icon ?? '₹';
    }
    public static function getEloquentQuery(): Builder
    {
        return AdminPayment::query()->where('payment_mode', Payment::CASH);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('invoice.client.user.profile')
                    ->circular()
                    ->label(__('messages.invoices'))
                    ->defaultImageUrl(function ($record) {
                        if (!$record->invoice->client->user->hasMedia(User::PROFILE)) {
                            return getUserImageInitial($record->id, $record->invoice->client->user->full_name);
                        }
                    })
                    ->url(fn($record) => ClientResource::getUrl('view', ['record' => $record->invoice->client->user->id]))
                    ->collection('profile')
                    ->width(50)->height(50),
                TextColumn::make('invoice.client.user.full_name')
                    ->label('')
                    ->color('primary')
                    ->html()
                    ->weight(FontWeight::SemiBold)
                    ->formatStateUsing(fn($record): View => view('filament.clusters.payments.columns.invoice_id', ['record' => $record]))

                    ->description(fn($record) => $record->invoice->client->user->email ?? __('messages.common.n/a'))
                    ->searchable(['users.first_name', 'users.last_name'])
                    ->sortable(['users.first_name', 'users.last_name']),
                TextColumn::make('payment_date')
                    ->label(__('messages.payment.payment_date'))
                    ->badge('primary')
                    ->date('Y-m-d')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('messages.quote.amount'))
                    ->formatStateUsing(function ($record) {
                        return getInvoiceCurrencyAmount($record->amount, $record->invoice->currency_id, true);
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_mode')
                    ->label(__('messages.payment.payment_mode'))
                    ->formatStateUsing(function ($record) {
                        return Payment::PAYMENT_MODE[$record->payment_mode];
                    })
                    ->badge('info')
            ])
            ->recordAction(null)
            ->filters([
                DateRangeFilter::make('payment_date')
                    ->placeholder(__('messages.client.created_at'))
                    ->defaultThisMonth()
                    ->label(__('messages.client.created_at')),
                // Filter::make('date')
                //     ->form([
                //         Select::make('date')
                //             ->label(__('messages.payment.payment_date'))
                //             ->options([
                //                 'today' => __('messages.datepicker.today'),
                //                 'yesterday' => __('messages.datepicker.yesterday'),
                //                 'last_7_days' => __('messages.datepicker.last_7_days'),
                //                 'last_30_days' => __('messages.datepicker.last_30_days'),
                //                 'this_month' => __('messages.datepicker.this_month'),
                //                 'last_month' => __('messages.datepicker.last_month'),
                //                 'custom' => __('messages.datepicker.custom'),
                //             ])
                //             ->native(false),
                //         DatePicker::make('start_date')
                //             ->label(__('messages.form.start_date'))
                //             ->visible(fn($get) => $get('date') == 'custom'),
                //         DatePicker::make('end_date')
                //             ->label(__('messages.form.end_date'))
                //             ->visible(fn($get) => $get('date') == 'custom'),
                //     ])
                //     ->query(
                //         function (Builder $query, array $data) {
                //             $dateRange = $data['date'] ?? null;
                //             switch ($dateRange) {
                //                 case 'today':
                //                     return $query->whereDate('payment_date', Carbon::today());
                //                     break;
                //                 case 'yesterday':
                //                     return $query->whereDate('payment_date', Carbon::yesterday());
                //                     break;
                //                 case 'last_7_days':
                //                     return $query->whereBetween('payment_date', [Carbon::now()->subDays(6), Carbon::today()]);
                //                     break;
                //                 case 'last_30_days':
                //                     return $query->whereBetween('payment_date', [Carbon::now()->subDays(28), Carbon::today()]);
                //                     break;
                //                 case 'this_month':
                //                     return $query->whereBetween('payment_date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                //                     break;
                //                 case 'last_month':
                //                     return $query->whereBetween('payment_date', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()]);
                //                     break;
                //                 case 'custom':
                //                     if (!empty($data['start_date']) && !empty($data['end_date'])) {
                //                         $query->whereBetween('payment_date', [$data['start_date'], $data['end_date']]);
                //                     }
                //                     break;
                //             }
                //         }
                //     )->indicateUsing(function ($data) {
                //         $dateRange = $data['date'] ?? null;
                //         if ($dateRange === 'custom' && !empty($data['start_date']) && !empty($data['end_date'])) {
                //             return __('messages.datepicker.custom') . ': ' . $data['start_date'] . ' - ' . $data['end_date'];
                //         }

                //         return match ($dateRange) {
                //             'today' => __('messages.datepicker.today'),
                //             'yesterday' => __('messages.datepicker.yesterday'),
                //             'last_7_days' => __('messages.datepicker.last_7_days'),
                //             'last_30_days' => __('messages.datepicker.last_30_days'),
                //             'this_month' => __('messages.datepicker.this_month'),
                //             'last_month' => __('messages.datepicker.last_month'),
                //             default => null,
                //         };
                //     }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->iconButton()->successNotificationTitle(__('messages.flash.payment_updated_successfully')),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->action(function (Payment $record) {

                        $record->delete();
                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.payment_deleted_successfully'))
                            ->send();
                    }),
            ])->actionsColumnLabel(__('messages.common.action'))
            ->bulkActions([])
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
            'index' => Pages\ListPayments::route('/'),
            // 'view' => Pages\ViewPayment::route('/{record}'),
        ];
    }
}
