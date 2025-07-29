<?php

namespace App\Livewire;

use App\Models\Tax;
use App\Models\Quote;
use Livewire\Component;
use App\Models\QuoteItem;
use Filament\Tables\Table;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Nette\Utils\Strings;

class QuoteItemTable extends Component implements HasTable, HasForms
{
    use InteractsWithForms;
    use InteractsWithTable;

    public $record;

    public $total;

    public $model;

    public $discount;

    public $subTotal;

    public $discount_type;

    public function mount($record)
    {
        $this->record = $record->id;
        $this->model = Quote::whereId($this->record)->first();
        $quoteItem = QuoteItem::where('quote_id', $this->record)->get();
        $this->subTotal = $quoteItem->sum(fn($item) => $item->price * $item->quantity);
        $this->discount = $this->model->discount;
        $this->discount_type = $this->model->discount_type;
        $this->total = $this->model->final_amount;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(QuoteItem::where('quote_id', $this->record)->with('quoteItemTax'))
            ->columns([
                TextColumn::make('product.name')
                    ->label(__('messages.product.product')),
                TextColumn::make('quantity')
                    ->alignEnd()
                    ->label(__('messages.quote.qty')),
                TextColumn::make('price')
                    ->formatStateUsing(fn($state) => getCurrencyAmount($state, true))
                    ->label(__('messages.quote.price')),
                TextColumn::make('quote_id')
                    ->label(__('messages.quote.tax'))
                    ->formatStateUsing(function ($record) {
                        $tax = [];
                        $getTaxes = Tax::whereIn('id', $record->quoteItemTax->pluck('tax_id'))->get();
                        foreach ($getTaxes as $taxes) {
                            $tax[] = $taxes->value;
                        }
                        return !empty($tax) ? implode(', ', $tax) : '';
                    }),

                TextColumn::make('id')
                    ->alignEnd()
                    ->formatStateUsing(fn($record) => $record->price * $record->quantity . ' ' . getCurrencySymbol())
                    ->label(__('messages.quote.amount')),
            ])
            ->paginated(false)
            ->defaultSort('created_at', 'desc');
    }

    public function render()
    {
        return view('livewire.quote-item-table');
    }
}
