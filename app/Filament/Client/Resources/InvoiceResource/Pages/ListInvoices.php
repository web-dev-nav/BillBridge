<?php

namespace App\Filament\Client\Resources\InvoiceResource\Pages;

use Exception;
use App\Models\Tax;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Quote;
use Livewire\Livewire;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Product;
use Twilio\Rest\Client;
use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\PaymentQrCode;
use App\Models\InvoiceItemTax;
use App\Models\InvoiceSetting;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Actions\CreateAction;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Illuminate\Contracts\View\View;
use Filament\Forms\Components\Group;
use Illuminate\Support\Facades\Mail;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Actions;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use App\Mail\InvoicePaymentReminderMail;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
// use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use App\Filament\Client\Resources\InvoiceResource;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\Action::make('excel')
                    ->label(__('messages.quote.excel_export'))
                    ->icon('heroicon-o-document-plus')
                    ->url(function () {
                        if (getLogInUser()->hasRole('admin')) {
                            return route('admin.invoicesExcel');
                        } elseif (getLogInUser()->hasRole('client')) {
                            return route('client.invoicesExcel');
                        }
                    }, shouldOpenInNewTab: true),
                Actions\Action::make('pdf')
                    ->label(__('messages.pdf_export'))
                    ->icon('heroicon-o-document-text')
                    ->url(function () {
                        if (getLogInUser()->hasRole('admin')) {
                            return route('admin.invoices.pdf');
                        } elseif (getLogInUser()->hasRole('client')) {
                            return route('client.invoices.pdf');
                        }
                    }, shouldOpenInNewTab: true),
            ])->button()->label(__('messages.common.export'))->icon('heroicon-s-chevron-down')->color('success'),
            CreateAction::make()->visible(getLogInUser()->hasRole('admin')),
        ];
    }

    public function table(Table $table): Table
    {

        return $table
            ->modifyQueryUsing(fn($query) => auth()->user()->hasRole('client') ? $query->where('client_id', auth()->user()->client->id)->where('status', '!=', Invoice::DRAFT) : $query)
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
                    ->icon(fn($record) => $record->recurring_status && getLogInUser()->hasRole('admin') ? 'heroicon-o-arrow-path' : null)
                    ->iconPosition(IconPosition::After)
                    ->formatStateUsing(fn($record): View => view(
                        'invoices.columns.full_name',
                        ['record' => $record],
                    ))
                    ->description(fn($record) => auth()->user()->hasRole('admin') ? $record->client->user->email : '')
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name', 'email', 'invoice_id']),

                TextColumn::make('invoice_date')
                    ->formatStateUsing(fn($record) => $record->invoice_date->format('Y-m-d'))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.invoice.invoice_date')),

                TextColumn::make('due_date')
                    ->formatStateUsing(fn($record) => $record->due_date->format('Y-m-d'))
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.quote.due_date')),
                TextColumn::make('final_amount')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn($record): string => getInvoiceCurrencyAmount($record->final_amount ?? 0, $record->currency_id, true))
                    ->label(__('messages.invoice.amount')),
                TextColumn::make('amount')
                    ->label(__('messages.invoice.transactions'))
                    ->sortable()
                    ->searchable()
                    ->alignCenter()
                    ->state(function ($record): View {
                        return view('invoices.columns.transactions', ['record' => $record]);
                    }),
                TextColumn::make('status')
                    ->alignCenter()
                    ->formatStateUsing(function ($record) {
                        if ($record->status_label === 'Paid') {
                            return __('messages.paid');
                        } elseif ($record->status_label === 'Unpaid') {
                            return __('messages.unpaid');
                        } elseif ($record->status_label === 'Partially Paid') {
                            return __('messages.partially paid');
                        } elseif ($record->status_label === 'Draft') {
                            return __('messages.draft');
                        } else {
                            return __('messages.overdue');
                        }
                    })
                    ->badge()
                    ->color(function ($record) {
                        if ($record->status_label === 'Paid') {
                            return 'success';
                        } elseif ($record->status_label === 'Unpaid') {
                            return 'danger';
                        } elseif ($record->status_label === 'Partially Paid') {
                            return 'primary';
                        } elseif ($record->status_label === 'Draft') {
                            return 'warning';
                        } else {
                            return 'danger';
                        }
                    })
                    ->searchable()
                    ->sortable()
                    ->label(__('messages.common.status')),
            ])
            ->filters([
                DateRangeFilter::make('created_at')
                    ->placeholder(__('messages.client.created_at'))
                    ->label(__('messages.client.created_at')),
                SelectFilter::make('status')
                    ->options(function () {
                        $statuses = Arr::except(Invoice::STATUS_ARR, Invoice::STATUS_ALL);
                        asort($statuses);
                        return $statuses;
                    })
                    ->placeholder(__('messages.common.status'))
                    ->native(false)
                    ->label(__('messages.common.status')),
                SelectFilter::make('recurring_status')
                    ->options(function () {
                        $statuses = Invoice::RECURRING_STATUS_ARR;
                        asort($statuses);
                        return $statuses;
                    })
                    ->placeholder(__('messages.invoice.recurring') . ' ' . __('messages.common.status'))
                    ->native(false)
                    ->label(__('messages.invoice.recurring') . ' ' . __('messages.common.status')),
            ])
            ->recordUrl(null)
            ->actionsColumnLabel(__('messages.common.action'))
            ->actions([
                Action::make('pdf')
                    ->iconButton()
                    ->tooltip(__('messages.invoice.download'))
                    ->icon('heroicon-o-arrow-down-tray')
                    ->hidden(fn($record) => getLogInUser()->hasRole('client'))
                    ->url(function ($record) {
                        if (auth()->user()->hasRole('admin')) {
                            return route('invoices.pdf', ['invoice' => $record->id]);
                        } elseif (auth()->user()->hasRole('client')) {
                            return route('clients.invoices.pdf', ['invoice' => $record->id]);
                        }
                    }, shouldOpenInNewTab: true),

                Action::make('payment')
                    ->iconButton()
                    ->tooltip(__('messages.invoice.make_payment'))
                    ->hidden(fn($record) => getLogInUser()->hasRole('admin') || $record->status == Invoice::PAID || $record->status == Invoice::DRAFT || $record->status == Invoice::PROCESSING)
                    ->icon('heroicon-o-banknotes')
                    ->url(fn($record) => route('filament.client.resources.invoices.payment', [$record->id])),

                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => in_array($record->status, [2, 3]) || getLogInUser()->hasRole('client')),

                    Tables\Actions\DeleteAction::make()
                        ->hidden(getLogInUser()->hasRole('client'))
                        ->successNotificationTitle(__('messages.flash.invoice_deleted_successfully')),

                    Tables\Actions\Action::make('invoice_url')
                        ->extraAttributes(fn($record) => ['onClick' => new HtmlString("copyURL('" . route('invoice-show-url', $record->invoice_id) . "')")])
                        ->icon('heroicon-o-link')
                        ->label(__('messages.invoice.invoice_url'))
                        ->hidden(fn($record) => $record->status === 0 || getLogInUser()->hasRole('client'))
                        ->successNotificationTitle(__('messages.flash.invoice_deleted_successfully')),

                    Action::make('payment_reminder')
                        ->label(__('messages.common.reminder'))
                        ->icon('heroicon-o-envelope')
                        ->hidden(fn($record) => in_array($record->status, [0, 2]) || getLogInUser()->hasRole('client')) // Hide if invoice is Paid or Draft
                        ->action(function ($record, $action) {
                            try {
                                $invoice = Invoice::with(['client.user', 'payments'])->whereId($record->id)->firstOrFail();
                                Mail::to($invoice->client->user->email)->send(new InvoicePaymentReminderMail($invoice));
                                Notification::make()
                                    ->success()
                                    ->title(__('messages.flash.payment_reminder_mail_send_successfully'))
                                    ->send();
                            } catch (Exception $e) {
                                Notification::make()
                                    ->danger()
                                    ->title($e->getMessage())
                                    ->send();
                                $action->halt();
                            }
                        }),

                    Action::make('recurring')
                        ->icon('heroicon-o-arrow-path')
                        ->label(fn($record) => $record->recurring_status ? __('messages.invoice.stop_recurring') : __('messages.invoice.start_recurring'))
                        ->hidden(fn($record) => !empty($record->parent_id) || getLogInUser()->hasRole('client')) // Hide if parent_id exists
                        ->action(function ($record) {
                            $recurringCycle = empty($record->recurring_cycle) ? 1 : $record->recurring_cycle;
                            $record->update([
                                'recurring_status' => !$record->recurring_status,
                                'recurring_cycle' => $recurringCycle,
                            ]);
                            Notification::make()
                                ->success()
                                ->title(__('messages.flash.recurring_status_updated_successfully'))
                                ->send();
                        }),
                    Action::make('pdf')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->label(__('messages.invoice.download'))
                        ->hidden(fn($record) => getLogInUser()->hasRole('admin'))
                        ->url(function ($record) {
                            if (auth()->user()->hasRole('admin')) {
                                return route('invoices.pdf', ['invoice' => $record->id]);
                            } elseif (auth()->user()->hasRole('client')) {
                                return route('clients.invoices.pdf', ['invoice' => $record->id]);
                            }
                        }, shouldOpenInNewTab: true),
                    Action::make('send_whatsapp')
                        ->icon('heroicon-o-chat-bubble-oval-left-ellipsis')
                        ->modalWidth('sm')
                        ->modalSubmitActionLabel(__('messages.invoice.send_whatsapp'))
                        ->modalHeading(__('messages.invoice.send_invoice_in_whatsapp'))
                        // ->hidden(fn($record) => getSettingValue('send_whatsapp_invoice') == 0 || in_array($record->status, [0, 2]))

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
                            $invoiceNo = $record->invoice_id;
                            $date = $record->invoice_date->format('d-m-y');
                            $dueDate = $record->due_date->format('d-m-y');
                            $totalAmount = number_format($record->final_amount, 2);
                            $paidAmount = number_format($record->payments->sum('amount'), 2);
                            $dueAmount = number_format($record->final_amount - $record->payments->sum('amount'), 2);
                            $pdfLink = route('invoices.pdf', $record->id);
                            $whatsappLink = "https://web.whatsapp.com/send?phone={$phoneNo}&text=" .
                                urlencode("Hello *{$name}*,\n\nThank you for doing business with *{$appName}*.\nPlease find your invoice details below.\n\n" .
                                    "Invoice No: {$invoiceNo}\nInvoice Date: {$date}\nDue Date: {$dueDate}\nTotal Amount: {$totalAmount}\n" .
                                    "Paid Amount: {$paidAmount}\nDue Amount: {$dueAmount}\n\nYou can view the invoice PDF here: {$pdfLink}");

                            return $this->dispatch('open-whatsapp-link', $whatsappLink);
                        })
                        // ->action(function ($record, array $data) {
                        //     try {

                        //         $twilioSid =  getSettingValue('twilio_sid') ?? env('TWILIO_SID');
                        //         $twilioToken = getSettingValue('twilio_token') ?? env('TWILIO_TOKEN');
                        //         $twilioWhatAppNumber = 'whatsapp:' . getSettingValue('twilio_from_number') ?? env('TWILIO_WHATSAPP_NUMBER');
                        //         $to = 'whatsapp:' . $data['phone'];

                        //         $message = "Hello *{$record->client->user->full_name}*,\n\n"
                        //             . "Thank you for doing business with *" . getAppName() . "*.\n"
                        //             . "Please find your invoice details below.\n\n"
                        //             . "Invoice No: {$record->invoice_id}\n"
                        //             . "Invoice Date: {$record->invoice_date->format('d-m-y')}\n"
                        //             . "Due Date: {$record->due_date->format('d-m-y')}\n"
                        //             . "Total Amount: " . number_format($record->final_amount, 2) . "\n"
                        //             . "Paid Amount: " . number_format($record->payments->sum('amount'), 2) . "\n"
                        //             . "Due Amount: " . number_format($record->final_amount - $record->payments->sum('amount'), 2) . "\n\n"
                        //             . "View invoice PDF: " . route('invoices.pdf', $record->id) . "\n\n";

                        //         $client = new Client($twilioSid, $twilioToken);
                        //         $message = $client->messages->create(
                        //             $to,
                        //             ['from' => $twilioWhatAppNumber, 'body' => $message]
                        //         );
                        //         Notification::make()
                        //             ->success()
                        //             ->title(__('Whatsapp message sent successfully'))
                        //             ->send();
                        //     } catch (Exception $e) {
                        //         Notification::make()
                        //             ->danger()
                        //             ->title($e->getMessage())
                        //             ->send();
                        //     }
                        // })
                        ->label(__('messages.invoice.send_whatsapp')),
                ])
            ])
            ->paginated([10, 25, 50]);
    }
}
