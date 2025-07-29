<?php

namespace App\Filament\Client\Resources\QuoteResource\Pages;

use Exception;
use App\Models\Quote;
use App\Models\QuoteItem;
use Filament\Actions;
use App\Models\Client;
use App\Models\Notification as NotificationModel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Client\Resources\QuoteResource;
use App\Repositories\QuoteItemRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EditQuote extends EditRecord
{
    protected static string $resource = QuoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    public function mutateFormDataBeforeFill(array $data): array
    {
        $quote = Quote::with([
            'quoteItems' => function ($query) {
                $query->with(['quoteItemTax']);
            },
            'client',
        ])->whereId($data['id'] ?? $this->record->id)->first();

        if (!$quote) {
            return $data;
        }

        $taxes = $quote->qouteTaxes()->pluck('tax_id')->toArray();
        $data['taxes'] = $taxes;

        $quoteItems = $quote->quoteItems->map(function ($quoteItem) {
            $product = '';
            if (!empty($quoteItem->product_name)) {
                $product = $quoteItem->product_name;
            } else {
                $product = $quoteItem->product_id;
            }

            return [
                'product_id' => $product,
                'quantity' => $quoteItem->quantity,
                'price' => $quoteItem->price,
                'item_tax' => $quoteItem->quoteItemTax->pluck('tax_id')->toArray(),
            ];
        })->toArray();

        $data['quoteItems'] = is_array($quoteItems) ? $quoteItems : [];

        return $data;
    }


    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        try {
            DB::beginTransaction();

            if (empty($data['discount_type'])) {
                $data['discount_type'] = 0;
            }

            if (auth()->user()->hasRole('client')) {
                $data['client_id'] = getLogInUserId();
                if ($data['client_id'] != Auth::id()) {
                    throw new UnprocessableEntityHttpException('Quote can\'t be updated.');
                }
            }

            // Ensure discount logic is applied
            if ($data['discount_type'] == 0) {
                $data['discount'] = 0;
            }
            $data['final_amount'] = $data['final_amount'];

            // Validate discount
            if (!empty($data['discount']) && $data['sub_total'] <= $data['discount']) {
                throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
            }

            // Ensure discount logic is applied
            if ($data['discount_type'] == 0) {
                $data['discount'] = 0;
            }

            $data['final_amount'] = $data['final_amount'];

            $client = Client::whereUserId($data['client_id'])->first();
            $quoteInvoiceTaxes = isset($data['taxes']) ? $data['taxes'] : [];
            $quoteItemInput = is_array($data['quoteItems']) ? $data['quoteItems'] : [];

            if (!$client) {
                throw new UnprocessableEntityHttpException('Client not found.');
            }
            $data['client_id'] = $client->id;

            $record->update(Arr::only($data, [
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
                'discount_before_tax'
            ]));
            $record->qouteTaxes()->detach();
            if (count($quoteInvoiceTaxes) > 0) {
                $record->qouteTaxes()->attach($quoteInvoiceTaxes);
            }

            $totalAmount = 0;

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

            $quoteItemRepo = app(QuoteItemRepository::class);
            $quoteItemRepo->updateQuoteItem($quoteItemInput, $record->id);
            $record->amount = $totalAmount;
            $record->save();


            DB::commit();

            $this->updateNotification($record);

            return $record;
        } catch (Exception $e) {
            DB::rollBack();
            Notification::make()
                ->danger()
                ->title($e->getMessage())->send();
            $this->halt();
        }
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.flash.quote_updated_successfully');
    }


    public function updateNotification(Quote $record)
    {
        try {
            $client = $record->client->user;
            addNotification([
                NotificationModel::NOTIFICATION_TYPE['Quote Updated'],
                $client->id,
                'Quote #' . $record->quote_id . ' has been updated.',
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
