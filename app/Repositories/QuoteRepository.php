<?php

namespace App\Repositories;

use App\Mail\QuoteCreateClientMail;
use App\Models\Client;
use App\Models\InvoiceSetting;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Setting;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Class QuoteRepository
 */
class QuoteRepository extends BaseRepository
{
    /**
     * @var string[]
     */
    public $fieldSearchable = [];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return Quote::class;
    }

    public function getProductNameList(): mixed
    {
        /** @var Product $product */
        static $product;

        if (! isset($product) && empty($product)) {
            $product = Product::toBase()->orderBy('name', 'asc')->pluck('name', 'id')->toArray();
        }

        return $product;
    }

    public function getQuoteItemList(array $quote = [])
    {
        /** @var QuoteItem $quoteItems */
        static $quoteItems;

        if (! isset($quoteItems) && empty($quoteItems)) {
            $quoteItems = QuoteItem::when($quote, function ($q) use ($quote) {
                $q->whereQuoteId($quote[0]->id);
            })->whereNotNull('product_name')->pluck('product_name', 'product_name')->toArray();
        }

        return $quoteItems;
    }

    public function getSyncList(array $quote = []): array
    {
        $data['products'] = $this->getProductNameList();
        if (! empty($quote)) {
            $data['productItem'] = $this->getQuoteItemList();
            $data['products'] = $data['products'] + $data['productItem'];
        }
        $data['associateProducts'] = $this->getAssociateProductList($quote);
        $data['discount_type'] = Quote::DISCOUNT_TYPE;
        $quoteStatusArr = Arr::only(Quote::STATUS_ARR, Quote::DRAFT);
        $quoteRecurringArr = Quote::RECURRING_ARR;
        $data['statusArr'] = $quoteStatusArr;
        $data['recurringArr'] = $quoteRecurringArr;
        $data['template'] = InvoiceSetting::toBase()->pluck('template_name', 'id')->toArray();

        return $data;
    }

    public function getAssociateProductList(array $quote = []): array
    {
        $result = $this->getProductNameList();
        if (! empty($quote)) {
            $quoteItem = $this->getQuoteItemList();
            $result = $result + $quoteItem;
        }
        $products = [];
        foreach ($result as $key => $item) {
            $products[] = [
                'key' => $key,
                'value' => $item,
            ];
        }

        return $products;
    }

    public function saveQuote(array $input): Quote
    {
        try {
            DB::beginTransaction();
            $input['final_amount'] = $input['amount'];
            if ($input['final_amount'] == 'NaN') {
                $input['final_amount'] = 0;
            }
            $quoteItemInputArray = Arr::only($input, ['product_id', 'quantity', 'price']);
            $quoteExist = Quote::where('quote_id', $input['quote_id'])->exists();
            $quoteItemInput = $this->prepareInputForQuoteItem($quoteItemInputArray);
            $total = [];
            foreach ($quoteItemInput as $key => $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }
            if ($quoteExist) {
                throw new UnprocessableEntityHttpException('Quote id already exist');
            }

            /** @var Quote $quote */
            $input['client_id'] = Client::whereUserId($input['client_id'])->first()->id;
            $input = Arr::only($input, [
                'client_id',
                'quote_id',
                'quote_date',
                'due_date',
                'discount_type',
                'discount',
                'final_amount',
                'note',
                'term',
                'template_id',
                'status',
            ]);
            $quote = Quote::create($input);
            $totalAmount = 0;
            foreach ($quoteItemInput as $key => $data) {
                $validator = Validator::make($data, QuoteItem::$rules, QuoteItem::$messages);

                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $data['product_name'] = is_numeric($data['product_id']);
                if ($data['product_name'] == true) {
                    $data['product_name'] = null;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }
                $data['amount'] = $data['price'] * $data['quantity'];

                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];

                /** @var QuoteItem $quoteItem */
                $quoteItem = new QuoteItem($data);

                $quoteItem = $quote->quoteItems()->save($quoteItem);
            }

            $quote->amount = $totalAmount;
            $quote->save();

            DB::commit();
            if (getSettingValue('mail_notification')) {
                $input['quoteData'] = $quote;
                $input['clientData'] = $quote->client->user;
                Mail::to($input['clientData']['email'])->send(new QuoteCreateClientMail($input));
            }

            return $quote;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    /**
     * @return Quote|Builder|Builder[]|Collection|Model
     */
    public function updateQuote($quoteId, $input)
    {
        try {
            DB::beginTransaction();
            if ($input['discount_type'] == 0) {
                $input['discount'] = 0;
            }
            $input['final_amount'] = $input['amount'];
            $quoteItemInputArr = Arr::only($input, ['product_id', 'quantity', 'price', 'id']);
            $quoteItemInput = $this->prepareInputForQuoteItem($quoteItemInputArr);
            $total = [];
            foreach ($quoteItemInput as $key => $value) {
                $total[] = $value['price'] * $value['quantity'];
            }
            if (! empty($input['discount'])) {
                if (array_sum($total) <= $input['discount']) {
                    throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
                }
            }

            /** @var Quote $quote */
            $input['client_id'] = Client::whereUserId($input['client_id'])->first()->id;
            $quote = $this->update(Arr::only(
                $input,
                [
                    'client_id',
                    'quote_date',
                    'due_date',
                    'discount_type',
                    'discount',
                    'final_amount',
                    'note',
                    'term',
                    'template_id',
                    'price',
                    'status',
                ]
            ), $quoteId);
            $totalAmount = 0;

            foreach ($quoteItemInput as $key => $data) {
                $validator = Validator::make($data, QuoteItem::$rules, QuoteItem::$messages);
                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }
                $data['product_name'] = is_numeric($data['product_id']);
                if ($data['product_name'] == true) {
                    $data['product_name'] = null;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }
                $data['amount'] = $data['price'] * $data['quantity'];
                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];
                $quoteItemInput[$key] = $data;
            }
            /** @var QuoteItemRepository $quoteItemRepo */
            $quoteItemRepo = app(QuoteItemRepository::class);
            $quoteItemRepo->updateQuoteItem($quoteItemInput, $quote->id);
            $quote->amount = $totalAmount;
            $quote->save();
            DB::commit();

            return $quote;
        } catch (Exception $exception) {
            throw new UnprocessableEntityHttpException($exception->getMessage());
        }
    }

    public function getPdfData($quote): array
    {
        $data = [];
        $data['quote'] = $quote;
        $data['client'] = $quote->client;

        $quoteItems = $quote->quoteItems;
        $data['invoice_template_color'] = !empty($quote->invoiceTemplate) ? $quote->invoiceTemplate->template_color : 'black';
        $data['setting'] = Setting::toBase()->pluck('value', 'key')->toArray();
        $data['totalTax'] = [];
        foreach ($quoteItems as $keys => $item) {
            $totalTax =  $item->quoteItemTax->sum('tax');
            $data['totalTax'][] = $item['quantity'] * $item['price'] * $totalTax / 100;
        }

        return $data;
    }


    public function getDefaultTemplate($quote): mixed
    {
        $data['invoice_template_name'] = $quote->invoiceTemplate->key;

        return $data['invoice_template_name'];
    }

    public function prepareInputForQuoteItem(array $input): array
    {
        $items = [];
        foreach ($input as $key => $data) {
            foreach ($data as $index => $value) {
                $items[$index][$key] = $value;
                if (! (isset($items[$index]['price']) && $key == 'price')) {
                    continue;
                }
                $items[$index]['price'] = removeCommaFromNumbers($items[$index]['price']);
            }
        }

        return $items;
    }

    public function saveNotification(array $input, $quote = null): void
    {
        $userId = $input['client_id'];
        $input['quote_id'] = $quote->quote_id;
        $title = 'New Quote created #' . $input['quote_id'] . '.';
        if ($input['status'] != Quote::DRAFT) {
            addNotification([
                Notification::NOTIFICATION_TYPE['Quote Created'],
                $userId,
                $title,
            ]);
        }
    }

    public function updateNotification($quote, $input, array $changes = [])
    {
        $quote->load('client.user');
        $userId = $quote->client->user_id;
        $title = 'Your Quote #' . $quote->quote_id . ' was updated.';
        if ($input['status'] != Quote::DRAFT) {
            if (isset($changes['status'])) {
                $title = 'Status of your Quote #' . $quote->quote_id . ' was updated.';
            }
            addNotification([
                Notification::NOTIFICATION_TYPE['Quote Updated'],
                $userId,
                $title,
            ]);
        }
    }

    public function getQuoteData($quote): array
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

        return $data;
    }

    public function prepareEditFormData($quote): array
    {
        /** @var Quote $quote */
        $quote = Quote::with([
            'quoteItems',
            'client',
        ])->whereId($quote->id)->first();

        $data = $this->getSyncList([$quote]);
        $data['client_id'] = $quote->client->user_id;
        $data['$quote'] = $quote;

        $data['quoteItems'] = $quote->quoteItems;

        return $data;
    }
}
