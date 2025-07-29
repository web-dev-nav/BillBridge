<?php

namespace App\Filament\Client\Resources\InvoiceResource\Pages;

use App\Models\Invoice;
use App\Models\Payment;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Http\Controllers\InvoiceController;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Http\Controllers\Client\PaypalController;
use App\Http\Controllers\Client\StripeController;
use App\Filament\Client\Resources\InvoiceResource;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\PaystackController;
use App\Http\Controllers\Client\RazorpayController;
use App\Http\Controllers\Client\MercadopagoController;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class InvoicePaymentPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = InvoiceResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.client.resources.invoice-resource.pages.invoice-payment-page';

    public ?array $data = [];
    public function getTitle(): string
    {
        return __('messages.payment.add_payment');
    }

    public function mount()
    {
        $invoiceID = request()->segment(3);
        $access = canAccess(Invoice::class, 'id', $invoiceID);

        if (!$access) {
            Notification::make()
                ->danger()
                ->title(__('messages.flash.seems_you_are_not_allowed_to_access_this_record'))
                ->send();
            return redirect(route('filament.client.resources.invoices.index'));
        }

        $invoice = Invoice::findOrFail($invoiceID);
        $currency = getInvoiceCurrencyIcon($invoice->currency_id);

        $dueAmount = 0;
        $paid = 0;
        foreach ($invoice->payments as $payment) {
            if ($payment->payment_mode == \App\Models\Payment::MANUAL && $payment->is_approved !== \App\Models\Payment::APPROVED) {
                continue;
            }
            $paid += $payment->amount;
        }
        $dueAmount = $invoice->final_amount - $paid;

        $this->data = [
            'payable_amount' => $dueAmount,
            'currency' => $currency,
            'invoice_id' => $invoiceID,
        ];

        $this->form->fill($this->data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }
    public function form(Form $form): Form
    {
        return $form
            ->model(Payment::class)
            ->schema([
                Section::make()
                    ->schema([
                        Hidden::make('invoice_id'),
                        TextInput::make('payable_amount')
                            ->numeric()
                            ->postfix($this->data['currency'])
                            ->label(__('messages.payment.payable_amount') . ':')
                            ->readOnly(),
                        Select::make('payment_type')
                            ->live()
                            ->label(__('messages.payment.payment_type') . ':')
                            ->native(false)
                            ->options(Payment::PAYMENT_TYPE)
                            ->placeholder(__('messages.payment.payment_type'))
                            ->required(),

                        TextInput::make('amount')
                            ->live()
                            ->visible(function ($get) {
                                if ($get('payment_type') == Payment::PARTIALLYPAYMENT) {
                                    return true;
                                }
                                return false;
                            })
                            ->afterStateUpdated(function ($state, $get, $set) {
                                if ($state > $get('payable_amount')) {
                                    $set('amount', '');
                                    return Notification::make()
                                        ->danger()
                                        ->title(__('messages.invoice.amount_should_be_less_than_payable_amount'))
                                        ->send();
                                }
                            })
                            ->numeric()
                            ->label(__('messages.invoice.amount') . ':')
                            ->placeholder(__('messages.invoice.amount'))
                            ->required(),

                        Select::make('payment_mode')
                            ->required()
                            ->label(__('messages.payment.payment_mode') . ':')
                            ->native(false)
                            ->searchable()
                            ->live()
                            ->options(app(InvoiceController::class)->getPaymentGateways())
                            ->placeholder(__('messages.payment.payment_mode')),

                        TextInput::make('transaction_id')
                            ->label(__('messages.payment.transaction_id') . ':')
                            ->placeholder(__('messages.payment.transaction_id')),

                        Textarea::make('notes')
                            ->label(__('messages.client.note') . ':')
                            ->placeholder(__('messages.client.notes'))
                            ->columnSpanFull()
                            ->rows(5)
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('payment_attachment')
                            ->hidden(function ($get) {
                                if ($get('payment_mode') == Payment::MANUAL) {
                                    return false;
                                }
                                return true;
                            })
                            ->label(__('messages.common.attachment') . ':')
                            ->disk(config('app.media_disk')),
                    ])->columns(2),
            ])->statePath('data')->columns(1);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('messages.common.pay'))
                ->submit('save'),
            Action::make('cancel')
                ->label(__('messages.common.cancel'))
                ->action('cancel')
                ->outlined(),
        ];
    }

    public function save()
    {
        $input = $this->data;
        if ($input['amount'] == null) {
            $input['amount'] = $input['payable_amount'];
        }

        $request = new Request($input);

        if ($input['payment_mode'] == Payment::MANUAL) {
            app(PaymentController::class)->store($request);
        }
        if ($input['payment_mode'] == Payment::STRIPE) {
            $request->request->add(['invoiceId' => $input['invoice_id']]);
            app(StripeController::class)->createSession($request);
        }
        if ($input['payment_mode'] == Payment::PAYPAL) {
            $request->request->add(['invoiceId' => $input['invoice_id']]);
            app(PaypalController::class)->onBoard($request);
        }
        if ($input['payment_mode'] == Payment::RAZORPAY) {
            $request->request->add(['invoiceId' => $input['invoice_id']]);
            $data =  app(RazorpayController::class)->onBoard($request);
            if (is_array($data)) {
                $this->js('razorPay(' .
                    json_encode($data['key']) . ', ' .
                    json_encode($data['name']) . ', ' .
                    json_encode($data['currency']) . ', ' .
                    $data['amount'] . ', ' .
                    json_encode($data['invoiceId']) . ', ' .
                    json_encode($data['email']) . ', ' .
                    json_encode($data['name']) . ')');
            }
        }
        if ($input['payment_mode'] == Payment::PAYSTACK) {
            $request->request->add(['invoiceId' => $input['invoice_id']]);
            app(PaystackController::class)->redirectToGateway($request);
        }

        if ($input['payment_mode'] == Payment::MERCADOPAGO) {
            $request->request->add(['invoiceId' => $input['invoice_id']]);
            $response = app(MercadopagoController::class)->redirectToGateway($request);
            if ($response instanceof \Illuminate\Http\JsonResponse) {
                $data = $response->getData();
                $this->js('openMercadoPago(' .
                    json_encode($data->publicKey) . ', ' .
                    json_encode($data->id) . ')');
            }
        }
    }

    public function cancel()
    {
        return redirect()->route('filament.client.resources.invoices.index');
    }
}
