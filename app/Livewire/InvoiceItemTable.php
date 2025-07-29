<?php

namespace App\Livewire;

use Livewire\Component;
use Filament\Tables\Table;
use App\Models\InvoiceItem;
use App\Models\Tax;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;

class InvoiceItemTable extends Component implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $record;
    public $currency_id;

    public function mount($record)
    {
        $this->record = $record->id;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(InvoiceItem::with('invoice')->where('invoice_id', $this->record))
            ->columns([
                TextColumn::make('id')
                    ->formatStateUsing(fn($record) => isset($record->product->name) ? $record->product->name : $record->product_name ?? 'N/A')
                    ->label(__('messages.product.product')),
                TextColumn::make('quantity')
                    ->label(__('messages.invoice.qty')),
                TextColumn::make('price')
                    ->formatStateUsing(fn($record) => getInvoiceCurrencyAmount($record->price, $record->invoice->currency_id, true))
                    ->label(__('messages.invoice.price')),
                TextColumn::make('invoiceItemTax')
                    ->label(__('messages.quote.tax') . ' (in %)')
                    ->formatStateUsing(function ($record) {
                        $tax = [];
                        $getTaxes = Tax::whereIn('id', $record->invoiceItemTax->pluck('tax_id'))->get();
                        foreach ($getTaxes as $taxes) {
                            $tax[] = $taxes->value;
                        }
                        return !empty($tax) ? implode(', ', $tax) : '';
                    })
                    ->html(),
                TextColumn::make('total')
                    ->label(__('messages.invoice.amount'))
                    ->formatStateUsing(fn($record) => isset($record->total) ? getInvoiceCurrencyAmount($record->total,$record->invoice->currency_id , true) : 'N/A')

            ])
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.invoice-item-table');
    }
}
