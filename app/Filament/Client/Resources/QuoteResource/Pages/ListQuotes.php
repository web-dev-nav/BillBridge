<?php

namespace App\Filament\Client\Resources\QuoteResource\Pages;

use App\Filament\Client\Resources\QuoteResource;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoiceItemTax;
use App\Models\Quote;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Filament\Actions;
use Filament\Tables\Actions\ActionGroup;
use Filament\Forms\Components\Hidden;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ListQuotes extends ListRecords
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\Action::make('excel')
                    ->label(__('messages.quote.excel_export'))
                    ->icon('heroicon-o-document-plus')
                    ->url(function () {
                        if (getLogInUser()->hasRole('admin')) {
                            return route('admin.quotesExcel');
                        } elseif (getLogInUser()->hasRole('client')) {
                            return route('client.quotesExcel');
                        }
                    }, shouldOpenInNewTab: true),
                Actions\Action::make('pdf')
                    ->label(__('messages.pdf_export'))
                    ->icon('heroicon-o-document-text')
                    ->url(function () {
                        if (getLogInUser()->hasRole('admin')) {
                            return route('admin.quotes.pdf');
                        } elseif (getLogInUser()->hasRole('client')) {
                            return route('client.export.quotes.pdf');
                        }
                    }, shouldOpenInNewTab: true),
            ])->button()->label(__('messages.common.export'))->icon('heroicon-s-chevron-down')->color('success'),
            Actions\CreateAction::make(),
        ];
    }


    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => auth()->user()->hasRole('client') ? $query->where('client_id', auth()->user()->client->id) : $query)
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('client.user.profile')
                    ->collection(User::PROFILE)
                    ->circular()
                    ->label(__('messages.client.client'))
                    ->width(50)
                    ->height(50)
                    ->sortable(['first_name'])
                    ->hidden(auth()->user()->hasRole('client'))
                    ->defaultImageUrl(function ($record) {
                        if (!$record->client->user->hasMedia(User::PROFILE)) {
                            return asset('images/avatar.png');
                        }
                    }),
                TextColumn::make('client.user.first_name')
                    ->label(auth()->user()->hasRole('client') ? __('messages.client.client') : '')
                    ->sortable(auth()->user()->hasRole('client') ? __('messages.client.client') : false)
                    ->html()
                    ->formatStateUsing(fn($record): View => view(
                        'client.columns.full_name',
                        ['record' => $record],
                    ))
                    ->description(fn($record) => auth()->user()->hasRole('admin') ? $record->client->user->email : '')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name', 'email', 'quote_id']),
                TextColumn::make('quote_date')
                    ->formatStateUsing(fn($record) => $record->quote_date->format('Y-m-d'))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.quote.quote_date')),
                TextColumn::make('due_date')
                    ->formatStateUsing(fn($record) => $record->due_date->format('Y-m-d'))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.quote.due_date')),
                TextColumn::make('final_amount')
                    ->formatStateUsing(fn($record) => getCurrencyAmount($record->final_amount, true))
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.quote.amount')),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn($record) => $record->status_label === 'Draft' ? __('messages.draft') : __('messages.converted'))
                    ->color(fn($record) => $record->status_label === 'Draft' ? 'warning' : 'success')
                    ->label(__('messages.common.status')),

            ])
            ->recordUrl(null)
            ->filters([
                DateRangeFilter::make('created_at')
                    ->placeholder(__('messages.client.created_at'))
                    ->label(__('messages.client.created_at')),
                SelectFilter::make('status')
                    ->options(function () {
                        $statuses = Quote::STATUS_ARR;
                        asort($statuses);
                        return $statuses;
                    })
                    ->placeholder(__('messages.common.status'))
                    ->native(false)
                    ->label(__('messages.common.status')),
            ])
            ->actionsColumnLabel(__('messages.common.action'))
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('convertToInvoice')
                        ->hidden(auth()->user()->hasRole('client'))
                        ->label(__('messages.quote.convert_to_invoice'))
                        ->action(function ($record, $action) {
                            try {
                                DB::beginTransaction();
                                $quoteId = $record->id;
                                $quote = Quote::whereId($quoteId)->firstOrFail();

                                $quoteDatas = self::getQuoteData($quote);
                                $quoteData = $quoteDatas['quote'];
                                $quoteItems = $quoteDatas['quote']['quoteItems'];
                                $data['selectedQuoteTaxes'] = $quote->qouteTaxes()->pluck('tax_id')->toArray();

                                if (! empty(getInvoiceNoPrefix())) {
                                    $quoteData['quote_id'] = getInvoiceNoPrefix() . '-' . $quoteData['quote_id'];
                                }
                                if (! empty(getInvoiceNoSuffix())) {
                                    $quoteData['quote_id'] .= '-' . getInvoiceNoSuffix();
                                }

                                $invoice['invoice_id'] = $quoteData['quote_id'];
                                $invoice['client_id'] = $quoteData['client_id'];
                                $invoice['invoice_date'] = Carbon::parse($quoteData['quote_date'])->format(currentDateFormat());
                                $invoice['due_date'] = Carbon::parse($quoteData['due_date'])->format(currentDateFormat());
                                $invoice['amount'] = $quoteData['amount'];
                                $invoice['final_amount'] = $quoteData['final_amount'];
                                $invoice['discount_type'] = $quoteData['discount_type'];
                                $invoice['discount'] = $quoteData['discount'];
                                $invoice['note'] = $quoteData['note'];
                                $invoice['term'] = $quoteData['term'];
                                $invoice['template_id'] = $quoteData['template_id'];
                                $invoice['recurring'] = $quoteData['recurring'];
                                $invoice['status'] = Invoice::DRAFT;
                                $invoice['discount_before_tax'] = $quoteData['discount_before_tax'];

                                $qouteTaxes = DB::table('quote_taxes')->where('quote_id', $quoteId)->get();
                                $invoice = Invoice::create($invoice);
                                if ($qouteTaxes->count() > 0) {
                                    foreach ($qouteTaxes as $quoteTax) {
                                        DB::table('invoice_taxes')->insert([
                                            'invoice_id' => $invoice->id,
                                            'tax_id' => $quoteTax->tax_id,
                                        ]);
                                    }
                                }

                                foreach ($quoteItems as $quoteItem) {
                                    $invoiceItem = InvoiceItem::create([
                                        'invoice_id' => $invoice->id,
                                        'product_id' => $quoteItem['product_id'],
                                        'product_name' => $quoteItem['product_name'],
                                        'quantity' => $quoteItem['quantity'],
                                        'price' => $quoteItem['price'],
                                        'total' => $quoteItem['total'],
                                    ]);

                                    foreach ($quoteItem->quoteItemTax as $index => $quoteItemTax) {
                                        InvoiceItemTax::create([
                                            'invoice_item_id' => $invoiceItem->id,
                                            'tax_id' => $quoteItemTax->tax_id,
                                            'tax' => $quoteItemTax->tax,
                                        ]);
                                    }
                                }

                                Quote::whereId($quoteId)->update(['status' => Quote::CONVERTED]);
                                DB::commit();
                                Notification::make()->success()->title(__('messages.flash.converted_to_invoice_successfully'))->send();
                            } catch (Exception $exception) {
                                DB::rollBack();
                                Notification::make()->danger()->title($exception->getMessage())->send();
                                $action->halt();
                            }
                        })
                        ->icon('heroicon-o-inbox-arrow-down')
                        ->visible(fn($record) => !$record->status),
                    Tables\Actions\EditAction::make()
                        ->visible(fn($record) => !$record->status),
                    Tables\Actions\DeleteAction::make()
                        ->successNotificationMessage(__('messages.flash.quote_deleted_successfully')),
                    Tables\Actions\Action::make('copy')
                        ->label(__('messages.quote.quote_url'))
                        ->icon('heroicon-o-link')
                        ->hidden(auth()->user()->hasRole('client'))
                        ->extraAttributes(fn($record) => [
                            'onClick' => new HtmlString("copyURL('" . route('quote-show-url', $record->quote_id) . "')"),
                        ]),
                    Tables\Actions\Action::make('download')
                        ->label(__('messages.quote.download'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->url(function ($record) {
                            if (auth()->user()->hasRole('admin')) {
                                return route('quotes.pdf', ['quote' => $record->id]);
                            } elseif (auth()->user()->hasRole('client')) {
                                return route('client.quotes.pdf', ['quote' => $record->id]);
                            }
                        }, shouldOpenInNewTab: true)
                        ->hidden(auth()->user()->hasRole('admin')),
                    Tables\Actions\Action::make('send_whatsapp')
                        ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                        ->modalWidth('sm')
                        ->modalSubmitActionLabel(__('messages.invoice.send_whatsapp'))
                        ->modalHeading(__('messages.invoice.send_invoice_in_whatsapp'))
                        ->form(function ($record) {
                            return [
                                PhoneInput::make('phone')
                                    ->label(__('messages.invoice.phone_number') . ':')
                                    ->key('phone')
                                    ->validationAttribute(__('messages.invoice.phone_number'))
                                    ->defaultCountry('IN')
                                    ->countryOrder(['in', 'us', 'gb'])
                                    ->required(),
                                Hidden::make('invoice_id')
                                    ->default($record->id)
                            ];
                        })
                        ->action(function ($record, array $data) {

                            $name = $record->client->user->full_name;
                            $phoneNo = $data['phone'];
                            $appName = getAppName();
                            $invoiceNo = $record->quote_id;
                            $date = $record->quote_date->format('d-m-y');
                            $dueDate = $record->due_date->format('d-m-y');
                            $totalAmount = number_format($record->final_amount, 2);
                            $pdfLink = route('quotes.pdf', $record->id);
                            $whatsappLink = "https://web.whatsapp.com/send?phone={$phoneNo}&text=" .
                                urlencode("Hello *{$name}*,\n\nThank you for doing business with *{$appName}*.\nPlease find your quote details below.\n\n" .
                                    "Quote No: {$invoiceNo}\nQuote Date: {$date}\nDue Date: {$dueDate}\nTotal Amount: {$totalAmount}\n" .
                                    "\n\nYou can view the quote PDF here: {$pdfLink}");

                            return $this->dispatch('open-whatsapp-link', $whatsappLink);
                        })
                        ->label(__('messages.invoice.send_whatsapp')),
                ]),
            ])
            ->bulkActions([])
            ->paginated([10, 25, 50]);
    }



    public static function getQuoteData($quote): array
    {
        $data = [];

        $quote = Quote::with([
            'client' => function ($query) {
                $query->select(['id', 'user_id', 'address']);
                $query->with([
                    'user' => function ($query) {
                        $query->select(['first_name', 'last_name', 'email', 'id', 'language']);
                    },
                ]);
            },
            'quoteItems',
        ])->whereId($quote->id)->first();

        $data['quote'] = $quote;
        $quoteItems = $quote->quoteItems;
        foreach ($quoteItems as $keys => $item) {
            $totalTax = $item->quoteItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        return $data;
    }
}
