<?php

namespace App\Filament\Client\Resources\QuoteResource\Pages;

use Exception;
use App\Models\Tax;
use App\Models\Quote;
use Filament\Actions;
use App\Models\Client;
use App\Models\Product;
use App\Models\QuoteItem;
use Illuminate\Support\Arr;
use App\Models\QuoteItemTax;
use Illuminate\Support\Facades\DB;
use App\Mail\QuoteCreateClientMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Repositories\QuoteRepository;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Validator;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Client\Resources\QuoteResource;
use App\Models\Notification as ModelsNotification;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateQuote extends CreateRecord
{
    protected static string $resource = QuoteResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function handleRecordCreation(array $input): Model
    {
        try {
            DB::beginTransaction();

            if (empty($input['discount_type'])) {
                $input['discount_type'] = 0;
            }

            if (auth()->user()->hasRole('client')) {
                $input['client_id'] = auth()->user()->id;
                $input['quote_id'] = $this->getModel()::generateUniqueQuoteId();
                if ($input['client_id'] != Auth::id()) {
                    throw new UnprocessableEntityHttpException('Quote can\'t be created.');
                }
            }

            if (!empty($input['discount']) && $input['sub_total'] <= $input['discount']) {
                throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
            }

            if (Quote::where('quote_id', $input['quote_id'])->exists()) {
                throw new UnprocessableEntityHttpException('Quote ID already exists.');
            }
            // dd($input);
            $inputQuoteTaxes = is_array($input['taxes']) ? $input['taxes'] : [];
            $quoteItemInput = is_array($input['quoteItems']) ? $input['quoteItems'] : [];

            $client = Client::whereUserId($input['client_id'])->first();
            if (!$client) {
                throw new UnprocessableEntityHttpException('Client not found.');
            }

            $input['client_id'] = $client->id;
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
                'discount_before_tax',
            ]);

            $quote = Quote::create($input);

            if (!empty($inputQuoteTaxes)) {
                foreach ($inputQuoteTaxes as $taxId) {
                    DB::table('quote_taxes')->insert([
                        'quote_id' => $quote->id,
                        'tax_id' => $taxId,
                    ]);
                }
            }

            $totalAmount = 0;
            $products = Product::toBase()->pluck('id')->toArray();

            foreach ($quoteItemInput as $key => $data) {
                if (!is_array($data)) {
                    throw new UnprocessableEntityHttpException('Invalid quote item data.');
                }

                $validator = Validator::make($data, QuoteItem::$rules, QuoteItem::$messages);
                if ($validator->fails()) {
                    throw new UnprocessableEntityHttpException($validator->errors()->first());
                }

                if (is_numeric($data['product_id']) && in_array($data['product_id'], $products)) {
                    $product = Product::find($data['product_id']);
                    $data['product_name'] = $product->name;
                } else {
                    $data['product_name'] = $data['product_id'];
                    $data['product_id'] = null;
                }

                $data['amount'] = $data['price'] * $data['quantity'];

                $data['total'] = $data['amount'];
                $totalAmount += $data['amount'];

                $quoteItem = new QuoteItem($data);
                $quoteItem = $quote->quoteItems()->save($quoteItem);

                $quoteItemTaxIds = isset($data['item_tax']) && is_array($data['item_tax']) ? $data['item_tax'] : [0];

                foreach ($quoteItemTaxIds as $taxId) {
                    $tax = Tax::find($taxId);
                    if ($tax) {
                        QuoteItemTax::create([
                            'quote_item_id' => $quoteItem->id,
                            'tax_id' => $taxId,
                            'tax' => $tax->value,
                        ]);
                    }
                }
            }

            $quote->amount = $totalAmount;
            $quote->save();

            DB::commit();
            // add mail
            if (getSettingValue('mail_notification')) {
                $input['quoteData'] = $quote;
                $input['clientData'] = $quote->client->user;
                Mail::to($input['clientData']['email'])->send(new QuoteCreateClientMail($input));
            }

            $this->createNotification($quote);

            return $quote;
        } catch (Exception $exception) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title($exception->getMessage())
                ->send();
            $this->halt();
        }
    }


    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.flash.quote_saved_successfully');
    }

    public function createNotification(Quote $quote)
    {
        try {
            $client = $quote->client->user;
            addNotification([
                ModelsNotification::NOTIFICATION_TYPE['Quote Created'],
                $client->id,
                'A new quote #' . $quote->quote_id . ' has been created for you.',
            ]);
        } catch (Exception $e) {
            Notification::make()
                ->danger()
                ->title($e->getMessage())
                ->send();
        }
    }
    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
