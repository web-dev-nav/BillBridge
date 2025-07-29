<?php

namespace App\Repositories;

use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\QuoteItemTax;
use App\Models\Tax;

/**
 * Class QuoteItemRepository
 *
 * @version February 24, 2020, 5:57 am UTC
 */
class QuoteItemRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'product_id',
        'quantity',
        'price',
        'tax',
        'total',
    ];

    /**
     * Return searchable fields
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model(): string
    {
        return QuoteItem::class;
    }

    public function updateQuoteItem(array $quoteItemInput, $quoteId)
    {

        /** @var Quote $quote */
        $quote = Quote::find($quoteId);
        $quoteItemIds = [];

        foreach ($quoteItemInput as $key => $data) {
            if (isset($data['id']) && ! empty($data['id'])) {
                $quoteItemIds[] = $data['id'];
                $this->update($data, $data['id']);
                $taxIds = ($data['item_tax'] != 0) ? $data['item_tax'] : $data['item_tax'] = [0 => 0];
                $this->addQuoteItemTax($data['id'], $taxIds, true);
                $this->removeQuoteItemTax($data['id'], $taxIds);
            } else {
                /** @var QuoteItem $quoteItem */
                $quoteItem = new QuoteItem($data);
                $quoteItem = $quote->quoteItems()->save($quoteItem);
                $quoteItemIds[] = $quoteItem->id;
                $taxIds = ($data['item_tax'] != 0) ? $data['item_tax'] : $data['item_tax'] = [0 => 0];
                $this->addQuoteItemTax($quoteItem->id, $taxIds);
                $quoteItemIds[] = $quoteItem->id;
            }
        }


        if (! (isset($quoteItemIds) && count($quoteItemIds))) {
            return;
        }

        QuoteItem::whereNotIn('id', $quoteItemIds)->whereQuoteId($quote->id)->delete();
    }

    public function addQuoteItemTax($quoteItemId, $taxIds, bool $checkDifference = false)
    {
        if (! $checkDifference) {
            foreach ($taxIds as $index => $taxId) {
                $tax = Tax::find($taxId);
                $quoteItemTax = QuoteItemTax::create([
                    'quote_item_id' => $quoteItemId,
                    'tax_id' => $taxId,
                    'tax' => $tax->value,
                ]);
            }

            return true;
        }
        $quoteItemTaxIds = QuoteItemTax::whereQuoteItemId($quoteItemId)->pluck('tax_id')->toArray();
        $taxDifference = array_diff($taxIds, $quoteItemTaxIds);
        if (! is_null($taxDifference)) {
            foreach ($taxDifference as $taxId) {
                $tax = Tax::find($taxId);
                $quoteItemTax = QuoteItemTax::create([
                    'quote_item_id' => $quoteItemId,
                    'tax_id' => $taxId,
                    'tax' => $tax->value,
                ]);
            }
        }
    }

    public function removeQuoteItemTax($quoteItemId, $taxIds)
    {
        $quoteItemTaxIds = QuoteItemTax::whereQuoteItemId($quoteItemId)->pluck('tax_id')->toArray();
        $taxDifference = array_diff($quoteItemTaxIds, $taxIds);
        foreach ($taxDifference as $index => $value) {
            QuoteItemTax::whereQuoteItemId($quoteItemId)->whereTaxId($value)->delete();
        }
    }
}
