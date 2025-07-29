<?php

namespace App\Filament\Client\Resources\InvoiceResource\Pages;

use Exception;
use Throwable;
use Filament\Actions;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Arr;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreateClientMail;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Facades\FilamentView;
use App\Models\Notification as ModelNotification;
use App\Filament\Client\Resources\InvoiceResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('messages.common.back'))
                ->outlined()
                ->url(static::getResource()::getUrl('index')),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
            $this->getSaveAndSendFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    public function getSaveAndSendFormAction(): Action
    {
        return Action::make('saveAndSend')
            ->label(__('messages.common.save_send'))
            ->requiresConfirmation()
            ->icon('heroicon-s-mail')
            ->iconPosition('start')
            ->action(fn() => $this->saveAndSend())
            ->hidden($this->getRecord()->status != Invoice::DRAFT)
            ->successNotificationTitle(__('messages.flash.invoice_updated_and_send_successfully'))
            ->color('primary');
    }

    public function saveAndSend(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState(afterValidate: function () {
                $this->callHook('afterValidate');

                $this->callHook('beforeSave');
            });

            $data = $this->mutateFormDataBeforeSave($data);

            $this->handleSaveAndSend($this->getRecord(), $data);

            $this->updateNotification($this->getRecord(), $data, $this->getRecord()->getChanges());

            $this->callHook('afterSave');

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction() ?
                $this->rollBackDatabaseTransaction() :
                $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        $this->rememberData();

        if ($shouldSendSavedNotification) {
            Notification::make()
                ->success()
                ->title(__('messages.flash.invoice_updated_and_send_successfully'))
                ->send();
        }

        if ($shouldRedirect && ($redirectUrl = $this->getRedirectUrl())) {
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && str($redirectUrl)->startsWith(request()->root()));
        }
    }

    public function updateNotification($invoice, $input, array $changes = [])
    {
        if (!empty($input['status']) && $input['status'] == Invoice::DRAFT) {
            $invoice->update([
                'status' => Invoice::UNPAID,
            ]);
            $invoice->load('client.user');
            $userId = $invoice->client->user_id;
            $title = 'Status of your invoice #' . $invoice->invoice_id . ' was updated.';
            addNotification([
                ModelNotification::NOTIFICATION_TYPE['Invoice Updated'],
                $userId,
                $title,
            ]);
            $input['invoiceData'] = $invoice->toArray();
            $input['clientData'] = $invoice->client->user->toArray();

            // add notification
            if (getSettingValue('mail_notification')) {
                Mail::to($invoice->client->user->email)->send(new InvoiceCreateClientMail($input));
            }

            return $invoice;
        } else {
            $invoice->load('client.user');
            $userId = $invoice->client->user_id;
            $title = 'Your invoice #' . $invoice->invoice_id . ' was updated.';
            if ($input['status'] != Invoice::DRAFT) {
                if (isset($changes['status'])) {
                    $title = 'Status of your invoice #' . $invoice->invoice_id . ' was updated.';
                }
                addNotification([
                    ModelNotification::NOTIFICATION_TYPE['Invoice Updated'],
                    $userId,
                    $title,
                ]);
            }
        }
    }

    public function handleRecordUpdate(Model $record, array $data): Model
    {
        $input = $data;
        try {
            return DB::transaction(function () use ($record, $input) {
                if ($input['status'] == Invoice::DRAFT) {
                    $input['status'] = Invoice::DRAFT;
                } else {
                    if ($input['status'] == Invoice::PROCESSING || $input['status'] == Invoice::PAID) {
                        $input['status'] = Invoice::UNPAID;
                    } else {
                        $input['status'] = $input['status'];
                    }
                }
                return $this->commonUpdate($record, $input);
            });
        } catch (Exception $exception) {
            $this->exceptionNotification($exception);
        }
    }

    public function handleSaveAndSend(Model $record, array $data): Model
    {
        $input = $data;
        if ($input['status'] == Invoice::DRAFT || $input['status'] == Invoice::PROCESSING || $input['status'] == Invoice::PAID) {
            $input['status'] = Invoice::UNPAID;
        } else {
            $input['status'] = $input['status'];
        }

        try {
            return DB::transaction(function () use ($record, $input) {
                return $this->commonUpdate($record, $input);
            });
        } catch (Exception $exception) {
            $this->exceptionNotification($exception);
        }
    }

    public function commonUpdate($record, $input)
    {
        $input['final_amount'] = removeCommaFromNumbers($input['total']);
        $input['amount'] = removeCommaFromNumbers($input['sub_total']);
        $input['taxes'] = $input['tax2'] ?? [];
        if (empty($input['discount_type'])) {
            $input['discount_type'] = 0;
        }

        $input['discount'] = removeCommaFromNumbers($input['discount'] ?? 0) ?? 0;


        if (!isset($input['note'])) {
            $input['note'] = null;
        }
        if (!isset($input['term'])) {
            $input['term'] = null;
        }

        if (($input['discount_type'] ?? 0) == 0) {
            $input['discount'] = 0;
        }

        $inputInvoiceTaxes = $input['taxes'] ?? [];

        if (!empty($input['recurring_status']) && empty($input['recurring_cycle'])) {
            throw new UnprocessableEntityHttpException('Please enter the value in Recurring Cycle.');
        }
        $subtotal = removeCommaFromNumbers($input['sub_total']) ?? 0;

        if (!empty($input['discount']) && $subtotal <= $input['discount']) {
            throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
        }

        $client = Client::whereUserId($input['client_id'])->first();
        if (!$client) {
            throw new UnprocessableEntityHttpException('Client not found.');
        }

        $input['client_id'] = $client->id;

        $record->fill(Arr::only($input, [
            'client_id',
            'invoice_date',
            'due_date',
            'discount_type',
            'discount',
            'amount',
            'discount_before_tax',
            'final_amount',
            'note',
            'term',
            'template_id',
            'payment_qr_code_id',
            'status',
            'tax_id',
            'tax',
            'currency_id',
            'recurring_status',
            'recurring_cycle',
        ]))->save();

        $record->invoiceTaxes()->sync($inputInvoiceTaxes);

        return $record;
    }

    public function exceptionNotification($exception)
    {
        Notification::make()
            ->danger()
            ->title($exception->getMessage())
            ->send();
        $this->halt();
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('messages.flash.invoice_updated_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
