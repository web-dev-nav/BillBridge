<?php

namespace App\Filament\Client\Resources\InvoiceResource\Pages;

use Filament\Actions;
use App\Models\Invoice;
use Mockery\Matcher\Not;
use App\Models\Notification;
use Filament\Infolists\Infolist;
use App\Livewire\InvoiceItemTable;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreateClientMail;
use Filament\Support\Enums\FontWeight;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Group;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\Livewire;
use App\Livewire\InvoicePaymentHistoryTable;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Actions\Action;
use App\Filament\Client\Resources\InvoiceResource;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Actions as ComponentsActions;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\Auth;

class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->hidden(auth()->user()->hasRole('client')),
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
                                                TextEntry::make('invoice_id')
                                                    ->label('')
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->formatStateUsing(fn($record) => __('messages.invoice.invoice') . ' #' . $record->invoice_id),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.quote.issue_for') . ':')
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<span>{$record->client->user->full_name}</span><br>
                                                    <span>" . ucfirst($record->client->address) ?? '' . "</span>")
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.quote.issue_by') . ':')
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<span>" . getAppName() . "</span><br>
                                                    <span>" . getSettingValue('company_address')  . "</span>")
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20),
                                            ])->columns(2),
                                            Group::make([
                                                TextEntry::make('invoice_date')
                                                    ->label(__('messages.invoice.invoice_date') . ':')
                                                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat(currentDateFormat()))
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->columns(1),
                                                TextEntry::make('due_date')
                                                    ->label(__('messages.invoice.due_date') . ':')
                                                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat(currentDateFormat()))
                                                    ->weight(FontWeight::SemiBold)
                                                    ->size(20)
                                                    ->columns(1),
                                                ComponentsActions::make([
                                                    Action::make('print_pdf')
                                                        ->label(__('messages.invoice.print_invoice'))
                                                        ->url(function ($record) {
                                                            if (Auth::user()->hasRole('admin')) {
                                                                return route('invoices.pdf', ['invoice' => $record->id]);
                                                            } else {
                                                                return route('clients.invoices.pdf', ['invoice' => $record->id]);
                                                            }
                                                        }, shouldOpenInNewTab: true)
                                                        ->color('success')
                                                ])->columns(1),
                                            ])->columns(3),
                                            Livewire::make(InvoiceItemTable::class)->data(fn($record) => $record->toArray())->columnSpanFull(),
                                            Group::make([
                                                ImageEntry::make('paymentQrCode.qr_image')
                                                    ->hiddenLabel()
                                                    ->size(120),
                                                RepeatableEntry::make('invoiceTaxes')
                                                    ->visible(fn($record) => count($record->invoiceTaxes) > 0)
                                                    ->label(__('messages.tax_information') . ': (%)')
                                                    ->schema([
                                                        TextEntry::make('value')
                                                            ->inlineLabel()
                                                            ->label('')
                                                            ->formatStateUsing(fn($state, $record) => "{$state}% ({$record->name})"),
                                                    ]),
                                                Group::make([])->hidden(fn($record) => count($record->invoiceTaxes) > 0),
                                                TextEntry::make('id')
                                                    ->html()
                                                    ->label('')
                                                    ->formatStateUsing(function ($record) {
                                                        $totalTax = collect($record->invoiceItems)->sum(
                                                            fn($item) =>
                                                            $item->quantity * $item->price * $item->invoiceItemTax->sum('tax') / 100
                                                        );

                                                        $itemTaxesAmount = $record->amount + $totalTax;
                                                        $invoiceTaxesAmount = ($itemTaxesAmount * $record->invoiceTaxes->sum('value')) / 100;
                                                        $percentageDiscount = $itemTaxesAmount + $invoiceTaxesAmount;

                                                        $discount = match (true) {
                                                            empty($record->discount) => 'N/A',
                                                            $record->discount_type == \App\Models\Invoice::FIXED => getInvoiceCurrencyAmount($record->discount, $record->currency_id, true),
                                                            default => getInvoiceCurrencyAmount(($percentageDiscount * $record->discount) / 100, $record->currency_id, true),
                                                        };

                                                        $subTotal = isset($record->amount) ? getInvoiceCurrencyAmount($record->amount, $record->currency_id, true) : 'N/A';
                                                        $totalTaxes = numberFormat($totalTax + $invoiceTaxesAmount) != 0 ? getInvoiceCurrencyAmount($totalTax + $invoiceTaxesAmount, $record->currency_id, true) : 'N/A';
                                                        $finalAmount = getInvoiceCurrencyAmount($record->final_amount, $record->currency_id, true);

                                                        return "
                                                        <div class='border-t border-gray-300 dark:border-gray-600 pt-2'>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.invoice.sub_total') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . $subTotal . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.invoice.discount') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . $discount . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 gap-6'>
                                                                <span class='text-gray-700 dark:text-white font-medium'>" . __('messages.taxes') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . $totalTaxes . "</span>
                                                            </div>
                                                            <div class='flex justify-between py-1 font-semibold gap-6'>
                                                                <span class='text-gray-700 dark:text-white'>" . __('messages.invoice.total') . ":</span>
                                                                <span class='text-gray-900 dark:text-white'>" . $finalAmount . "</span>
                                                            </div>
                                                        </div>
                                                    ";
                                                    })
                                                    ->alignEnd()
                                            ])->columns(3)->columnSpanFull()
                                        ])->columnSpan(3),
                                    Group::make([
                                        Section::make()
                                            ->heading(__('messages.invoice.client_overview'))
                                            ->schema([
                                                TextEntry::make('status')
                                                    ->label('')
                                                    ->badge()
                                                    ->formatStateUsing(function ($state) {
                                                        if ($state == Invoice::UNPAID) {
                                                            return __('messages.pending_payment');
                                                        } elseif ($state == Invoice::PAID) {
                                                            return __('messages.paid');
                                                        } elseif ($state == Invoice::PARTIALLY) {
                                                            return __('messages.partially paid');
                                                        } elseif ($state == Invoice::DRAFT) {
                                                            return __('messages.draft');
                                                        } elseif ($state == Invoice::OVERDUE) {
                                                            return __('messages.overdue');
                                                        } elseif ($state == Invoice::PROCESSING) {
                                                            return __('messages.processing');
                                                        } else {
                                                            return '';
                                                        }
                                                    })
                                                    ->color(function ($state) {
                                                        if ($state == Invoice::UNPAID) {
                                                            return 'danger';
                                                        } elseif ($state == Invoice::PAID) {
                                                            return 'success';
                                                        } elseif ($state == Invoice::PARTIALLY) {
                                                            return 'primary';
                                                        } elseif ($state == Invoice::DRAFT) {
                                                            return 'warning';
                                                        } elseif ($state == Invoice::OVERDUE) {
                                                            return 'danger';
                                                        } elseif ($state == Invoice::PROCESSING) {
                                                            return 'primary';
                                                        } else {
                                                            return 'secondary';
                                                        }
                                                    }),
                                                ComponentsActions::make([
                                                    Action::make('send')
                                                        ->size('sm')
                                                        ->requiresConfirmation()
                                                        ->modalHeading(__('js.send_invoice'))
                                                        ->modalDescription(__('messages.invoice.are_you_sure_send'))
                                                        ->modalSubmitActionLabel(__('js.yes_send'))
                                                        ->modalCancelActionLabel(__('js.no_cancel'))
                                                        ->modalIconColor('warning')
                                                        ->modalIcon('heroicon-o-exclamation-circle')
                                                        ->action(fn($record) => self::draftStatusUpdate($record))
                                                        ->label(__('messages.invoice.send'))
                                                        ->color('success')
                                                ])->hidden(fn($record) => $record->status != Invoice::DRAFT),
                                                TextEntry::make('client.user.full_name')
                                                    ->label(__('messages.invoice.client_name'))
                                                    ->weight(FontWeight::Medium)
                                                    ->url(route('filament.admin.resources.clients.view', ['record' => $this->record->client->id]))
                                                    ->color('primary'),
                                                TextEntry::make('client.user.email')
                                                    ->label(__('messages.invoice.client_email'))
                                                    ->html()
                                                    ->formatStateUsing(fn($record) => "<a href='mailto:{$record->client->user->email}'>{$record->client->user->email}</a>"),
                                                TextEntry::make('amount')
                                                    ->label(__('messages.invoice.paid_amount'))
                                                    ->formatStateUsing(fn($record) => getInvoiceCurrencyAmount(getInvoicePaidAmount($record->id), $record->currency_id, true)),
                                                TextEntry::make('id')
                                                    ->label(__('messages.invoice.remaining_amount'))
                                                    ->formatStateUsing(fn($record) => getInvoiceCurrencyAmount(getInvoiceDueAmount($record->id), $record->currency_id, true)),
                                                TextEntry::make('last_recurring_on')
                                                    ->default('N/A')
                                                    ->label(__('messages.invoice.last_recurring_on'))
                                                    ->visible(fn($record) => $record->recurring_status)
                                                    ->formatStateUsing(function ($record) {
                                                        if ($record->last_recurring_on) {
                                                            return \Carbon\Carbon::parse($record->last_recurring_on)->translatedFormat(currentDateFormat());
                                                        } else {
                                                            return 'N/A';
                                                        }
                                                    }),
                                                TextEntry::make('recurring_cycle')
                                                    ->label(__('messages.invoice.recurring_cycle'))
                                                    ->visible(fn($record) => $record->recurring_status),
                                            ])
                                    ])->columns(3),
                                ])->columns(4)
                            ]),
                        Tabs\Tab::make(__('messages.quote.note_terms'))
                            ->schema([
                                TextEntry::make('note')
                                    ->label(__('messages.quote.note') . ':')
                                    ->default(__('messages.common.n/a')),
                                TextEntry::make('term')
                                    ->label(__('messages.quote.terms') . ':')
                                    ->default(__('messages.common.n/a')),
                            ]),
                        Tabs\Tab::make(__('messages.invoice.payment_history'))
                            ->schema([
                                Livewire::make(InvoicePaymentHistoryTable::class)->data(fn($record) => (['invoiceId' => $record->id]))->key(fn($record) => $record->id),
                            ])
                    ])->columnSpanFull(),
            ]);
    }

    private function draftStatusUpdate($invoice)
    {
        try {
            $invoice->update([
                'status' => Invoice::UNPAID,
            ]);
            $invoice->load('client.user');
            $userId = $invoice->client->user_id;
            $title = 'Status of your invoice #' . $invoice->invoice_id . ' was updated.';
            addNotification([
                Notification::NOTIFICATION_TYPE['Invoice Updated'],
                $userId,
                $title,
            ]);
            $input['invoiceData'] = $invoice->toArray();
            $input['clientData'] = $invoice->client->user->toArray();
            if (getSettingValue('mail_notification')) {
                Mail::to($invoice->client->user->email)->send(new InvoiceCreateClientMail($input));
            }

            return $invoice;
        } catch (\Exception $e) {
            FilamentNotification::make()->danger()->title($e->getMessage())->send();
        }
    }
}
