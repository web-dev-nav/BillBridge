<?php

namespace App\Livewire;

use App\Models\Payment;
use Livewire\Component;
use Filament\Tables\Table;
use App\Models\InvoiceItem;
use Illuminate\Contracts\View\View;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class InvoicePaymentHistoryTable extends Component implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $invoiceId;

    public function table(Table $table): Table
    {
        return $table
            ->query(Payment::where('invoice_id', $this->invoiceId))
            ->columns([
                TextColumn::make('payment_date')
                    ->sortable()
                    ->searchable()
                    ->label(__('messages.payment.payment_date'))
                    ->badge()
                    ->formatStateUsing(fn($state) => \Carbon\Carbon::parse($state)->translatedFormat(currentDateFormat())),
                TextColumn::make('amount')
                    ->sortable()
                    ->searchable()
                    ->label(__('messages.invoice.paid_amount'))
                    ->formatStateUsing(fn($record) => getCurrencyAmount($record->amount, true)),
                TextColumn::make('payment_mode')
                    ->searchable()
                    ->label(__('messages.payment.payment_method'))
                    ->formatStateUsing(fn($record): View => view('transactions.payment_mode', ['record' => $record])),
                TextColumn::make('id')
                    ->searchable()
                    ->label(__('messages.common.status'))
                    ->badge()
                    ->formatStateUsing(function ($record) {
                        if ($record->is_approved == \App\Models\Payment::APPROVED && $record->payment_mode == 1) {
                            return \App\Models\Payment::PAID;
                        } elseif ($record->is_approved == \App\Models\Payment::PENDING && $record->payment_mode == 1) {
                            return \App\Models\Payment::PROCESSING;
                        } elseif ($record->is_approved == \App\Models\Payment::REJECTED && $record->payment_mode == 1) {
                            return \App\Models\Payment::DENIED;
                        } else {
                            return \App\Models\Payment::PAID;
                        }
                    })
                    ->color(function ($record) {
                        if ($record->is_approved == \App\Models\Payment::APPROVED && $record->payment_mode == 1) {
                            return 'success';
                        } elseif ($record->is_approved == \App\Models\Payment::PENDING && $record->payment_mode == 1) {
                            return 'danger';
                        } elseif ($record->is_approved == \App\Models\Payment::REJECTED && $record->payment_mode == 1) {
                            return 'danger';
                        } else {
                            return 'success';
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.invoice-payment-history-table');
    }
}
