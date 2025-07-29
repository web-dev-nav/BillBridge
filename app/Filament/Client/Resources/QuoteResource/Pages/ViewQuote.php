<?php

namespace App\Filament\Client\Resources\QuoteResource\Pages;

use App\Models\Tax;
use App\Models\Quote;
use Filament\Actions;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Livewire\QuoteItemTable;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\DB;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Client\Resources\QuoteResource;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Actions as ComponentsActions;
use Illuminate\Support\Facades\Auth;

class ViewQuote extends ViewRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make(__('messages.quote.overview'))
                            ->schema([
                                Group::make([
                                    Fieldset::make('')
                                        ->schema([
                                            Group::make([
                                                ImageEntry::make('app_logo')
                                                    ->label('')
                                                    ->stacked()
                                                    ->default(getLogoUrl()),
                                                TextEntry::make('quote_id')
                                                    ->label('')
                                                    ->inlineLabel()
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->formatStateUsing(fn($record) => __('messages.quote.quote') . ' #' . $record->quote_id),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.quote.issue_for') . ':')
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<span class='text-lg font-bold'>{$record->client->user->full_name}</span><br>
                                                    <span class='text-md'>" . ucfirst($record->client->address) ?? '' . "</span>"),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.quote.issue_by') . ':')
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<span class='text-lg font-bold'>" . getAppName() . "</span><br>
                                                    <span class='text-md'>" . getSettingValue('company_address')  . "</span>"),

                                            ])->columns(2),
                                            Group::make([
                                                TextEntry::make('quote_date')
                                                    ->label(__('messages.quote.quote_date') . ':')
                                                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat(currentDateFormat()))
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->columns(1),
                                                TextEntry::make('due_date')
                                                    ->label(__('messages.quote.due_date') . ':')
                                                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat(currentDateFormat()))
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->columns(1),
                                                ComponentsActions::make([
                                                    Action::make('print_pdf')
                                                        ->label(__('messages.quote.print_quote'))
                                                        ->url(function ($record) {
                                                            if (Auth::user()->hasRole('admin')) {
                                                                return route('quotes.pdf', ['quote' => $record->id]);
                                                            } else {
                                                                return route('client.quotes.pdf', ['quote' => $record->id]);
                                                            }
                                                        }, shouldOpenInNewTab: true)
                                                        ->color('success')
                                                ])->columns(1),
                                            ])->columns(3),
                                            Livewire::make(QuoteItemTable::class)->columnSpanFull(),
                                            TextEntry::make('id')
                                                ->html()
                                                ->label('')
                                                ->formatStateUsing(function ($record) {
                                                    $itemWiseTaxes = 0;
                                                    $totalAmount = 0;
                                                    foreach ($record->quoteItems as $item) {
                                                        $itemTotal = $item->quantity * $item->price;
                                                        $totalAmount += $itemTotal;

                                                        if ($item->quoteItemTax->isNotEmpty()) {
                                                            $taxes = Tax::whereIn('id', $item->quoteItemTax->pluck('tax_id'))->get();

                                                            foreach ($taxes as $tax) {
                                                                $itemWiseTaxes += ($itemTotal * $tax->value) / 100;
                                                            }
                                                        }
                                                    }

                                                    $subTotal = $record->quoteItems->sum(fn($item) => $item->quantity * $item->price);

                                                    $totalQuoteTax = DB::table('quote_taxes')
                                                        ->where('quote_id', $record->id)
                                                        ->join('taxes', 'quote_taxes.tax_id', '=', 'taxes.id')
                                                        ->sum('taxes.value');
                                                    $totalTaxAmount = ($subTotal + $itemWiseTaxes) * ($totalQuoteTax / 100);
                                                    $finalTaxAmount = $itemWiseTaxes + $totalTaxAmount;

                                                    $discount = 0;
                                                    if (!empty($record->discount)) {
                                                        if ($record->discount_type == \App\Models\Invoice::FIXED) {
                                                            $discount = $record->discount;
                                                        } else {
                                                            $isDiscountBeforeTax = $record->discount_before_tax;
                                                            if ($isDiscountBeforeTax) {
                                                                $discount = ($subTotal * $record->discount) / 100;
                                                                $subTotal -= $discount;
                                                                $totalTaxAmount = ($subTotal + $itemWiseTaxes) * ($totalQuoteTax / 100);
                                                            } else {
                                                                $totalWithTax = $subTotal + $itemWiseTaxes + $totalTaxAmount;
                                                                $discount = ($totalWithTax * $record->discount) / 100;
                                                            }
                                                        }
                                                    }

                                                    $finalAmount = ($subTotal + $finalTaxAmount) - $discount;

                                                    return "
                                                        <div class='border-t border-gray-300 dark:border-gray-600 pt-2'>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.invoice.sub_total') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . getCurrencyAmount($subTotal, true) . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.invoice.discount') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . getCurrencyAmount($discount, true) . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.taxes') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . getCurrencyAmount($finalTaxAmount, true) . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 font-semibold gap-6'>
                                                                <span class='text-gray-700 dark:text-white'>" . __('messages.invoice.total') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . getCurrencyAmount($finalAmount, true) . "</span>
                                                            </div>
                                                        </div>
                                                    ";
                                                })
                                                ->alignEnd()
                                                ->columnSpanFull()
                                        ])->columnSpan(2),
                                    Group::make([
                                        Section::make()
                                            ->heading(__('messages.invoice.client_overview'))
                                            ->schema([
                                                TextEntry::make('status')
                                                    ->label('')
                                                    ->badge()
                                                    ->formatStateUsing(fn($state) => Quote::STATUS_ARR[$state])
                                                    ->color(fn($state) => $state == Quote::DRAFT ? 'warning' : 'success'),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.invoice.client_name'))
                                                    ->weight(FontWeight::Bold)
                                                    ->url(route('filament.admin.resources.clients.view', ['record' => $this->record->client->id]))
                                                    ->color('primary'),
                                                TextEntry::make('client.user.email')
                                                    ->label(__('messages.invoice.client_email'))
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<a class='font-bold' href='mailto:{$record->client->user->email}'>{$record->client->user->email}</a>")
                                                    ->color('primary'),
                                            ])
                                    ])->columns(3),
                                ])->columns(3)
                            ]),
                        Tabs\Tab::make(__('messages.quote.note_terms'))
                            ->schema([
                                TextEntry::make('note')
                                    ->label(__('messages.quote.note') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('term')
                                    ->label(__('messages.quote.terms') . ':')
                                    ->default(__('messages.common.n/a')),
                            ])
                    ])->columnSpanFull(),
            ]);
    }

    public function getPdfData($quote)
    {
        $data = [];
        $data['quote'] = $quote;
        $data['client'] = $quote->client;
        $quoteItems = $quote->quoteItems;
        $data['invoice_template_color'] = $quote->invoiceTemplate->template_color;
        $data['setting'] = Setting::toBase()->pluck('value', 'key')->toArray();

        return $data;
    }

    public function getDefaultTemplate($quote): mixed
    {
        $data['invoice_template_name'] = $quote->invoiceTemplate->key;

        return $data['invoice_template_name'];
    }
}
