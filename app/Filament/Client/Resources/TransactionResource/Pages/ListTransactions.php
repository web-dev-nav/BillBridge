<?php

namespace App\Filament\Client\Resources\TransactionResource\Pages;

use App\Filament\Client\Resources\TransactionResource;
use App\Models\Invoice;
use App\Models\Payment;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\Facades\Auth;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('excel')
                    ->label(__('messages.invoice.excel_export'))
                    ->icon('heroicon-o-document-plus')
                    ->url(
                        function(){
                            if(Auth::user()->hasRole('admin')){
                                return route('admin.transactionsExcel');
                            }else{
                                return route('client.transactionsExcel');
                            }
                        }
                        , shouldOpenInNewTab: true),
                Action::make('excel')
                    ->label(__('messages.pdf_export'))
                    ->icon('heroicon-o-document-text')
                    ->url( function(){
                        if(Auth::user()->hasRole('admin')){
                            return route('admin.export.transactions.pdf');
                        }else{
                            return route('client.export.transactions.pdf');
                        }
                    }, shouldOpenInNewTab: true)
            ])
                ->icon('heroicon-o-chevron-down')
                ->iconPosition(IconPosition::After)
                ->label(__('messages.common.actions'))
                ->button()
        ];
    }

    public function changeStatus($status, Payment $payment)
    {
        try {
            if ($status == Payment::MANUAL) {
                $payment->update([
                    'is_approved' => $status,
                ]);
                $this->updatePayment($payment);

                return Notification::make()->success()->title(__('messages.flash.manual_payment_approved_successfully'))->send();
            }

            $payment->update([
                'is_approved' => $status,
            ]);
            $this->updatePayment($payment);
            return Notification::make()->success()->title(__('messages.flash.manual_payment_denied_successfully'))->send();
        } catch (Exception $e) {
            return Notification::make()->danger()->title($e->getMessage())->send();
        }
    }

    private function updatePayment(Payment $payment): void
    {
        $paymentInvoice = $payment->invoice;
        $totalPayment = Payment::whereInvoiceId($paymentInvoice->id)->whereIsApproved(Payment::APPROVED)->sum('amount');
        $status = Invoice::PARTIALLY;
        if ($payment->amount == $paymentInvoice->final_amount || $totalPayment == $paymentInvoice->final_amount) {
            $status = $payment->is_approved == Payment::APPROVED ? Invoice::PAID : Invoice::UNPAID;
        } elseif ($totalPayment == 0) {
            $status = Invoice::UNPAID;
        }
        $paymentInvoice->update([
            'status' => $status,
        ]);
    }
}
