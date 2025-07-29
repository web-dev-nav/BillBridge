<?php

namespace App\Filament\Client\Resources\InvoiceResource\Pages;

use Exception;
use Throwable;
use Filament\Actions;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Arr;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceCreateClientMail;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Facades\FilamentView;
use App\Models\Notification as ModelNotification;
use App\Filament\Client\Resources\InvoiceResource;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class CreateInvoice extends CreateRecord
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
            $this->getCreateFormAction(),
            $this->getSaveAndSendFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('messages.common.save_draft'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    public function getSaveAndSendFormAction(): Action
    {
        return Action::make('saveAndSend')
            ->label(__('messages.common.save_send'))
            ->requiresConfirmation()
            ->icon('heroicon-s-mail')
            ->iconPosition('start')
            ->action(fn() => $this->saveAndSend())
            ->color('primary');
    }

    public function saveAndSend(bool $another = false)
    {
        $this->authorizeAccess();

        try {
            $this->beginDatabaseTransaction();

            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeCreate($data);

            $this->callHook('beforeCreate');

            $this->record = $this->handleSaveAndSend($data);

            $this->saveNotification($data, $this->record);

            $this->form->model($this->getRecord())->saveRelationships();

            $this->callHook('afterCreate');

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

        Notification::make()
            ->success()
            ->title(__('messages.flash.invoice_saved_and_sent_successfully'))
            ->send();

        if ($another) {
            // Ensure that the form record is anonymized so that relationships aren't loaded.
            $this->form->model($this->getRecord()::class);
            $this->record = null;

            $this->fillForm();

            return;
        }

        $redirectUrl = $this->getResource()::getUrl('index');

        //redirect with spa
        return $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && str($redirectUrl)->startsWith(request()->root()));
    }


    public function handleSaveAndSend($input): Model
    {
        if ($input['status'] == Invoice::PAID || $input['status'] == Invoice::PROCESSING) {
            $input['status'] = Invoice::UNPAID;
        } else {
            $input['status'] = $input['status'];
        }
        try {
            DB::beginTransaction();
            return $this->commonSave($input);
        } catch (Exception $exception) {
            $this->exceptionNotification($exception);
        }
    }

    public function handleRecordCreation($input): Model
    {

        try {
            DB::beginTransaction();
            $input['status'] = Invoice::DRAFT;

            return  $this->commonSave($input);
        } catch (Exception $exception) {
            $this->exceptionNotification($exception);
        }
    }

    public function saveNotification(array $input, $invoice = null): void
    {
        $userId = $input['client_id'];
        $input['invoice_id'] = $invoice->invoice_id;
        $title = 'New invoice created #' . $input['invoice_id'] . '.';

        if ($input['status'] != Invoice::DRAFT) {
            addNotification([
                ModelNotification::NOTIFICATION_TYPE['Invoice Created'],
                $userId,
                $title,
            ]);
        }
    }

    public function commonSave($input)
    {

        $input['final_amount'] = removeCommaFromNumbers($input['total']);
        $input['amount'] = removeCommaFromNumbers($input['sub_total']);
        $input['taxes'] = $input['tax2'];
        $input['discount'] = empty($input['discount']) ? 0.00 : $input['discount'];

        if (! empty(getInvoiceNoPrefix())) {
            $input['invoice_id'] = getInvoiceNoPrefix() . '-' . $input['invoice_id'];
        }
        if (! empty(getInvoiceNoSuffix())) {
            $input['invoice_id'] .= '-' . getInvoiceNoSuffix();
        }

        if (empty($input['final_amount'])) {
            $input['final_amount'] = 0;
        }

        if (empty($input['discount_type'])) {
            $input['discount_type'] = 0;
        }

        if (! empty($input['recurring_status']) && empty($input['recurring_cycle'])) {
            throw new UnprocessableEntityHttpException('Please enter the value in Recurring Cycle.');
        }

        $inputInvoiceTaxes = isset($input['taxes']) ? $input['taxes'] : [];
        $invoiceExist = Invoice::where('invoice_id', $input['invoice_id'])->exists();

        if (! empty($input['discount'])) {
            if (($input['sub_total']) <= $input['discount']) {
                throw new UnprocessableEntityHttpException('Discount amount should not be greater than sub total.');
            }
        }
        if ($invoiceExist) {
            throw new UnprocessableEntityHttpException('Invoice id already exist');
        }
        /** @var Invoice $invoice */
        $input['client_id'] = Client::whereUserId($input['client_id'])->first()->id;
        $input = Arr::only($input, [
            'client_id',
            'invoice_id',
            'invoice_date',
            'due_date',
            'discount_type',
            'discount',
            'amount',
            'final_amount',
            'note',
            'term',
            'template_id',
            'discount_before_tax',
            'payment_qr_code_id',
            'status',
            'tax_id',
            'tax',
            'currency_id',
            'recurring_status',
            'recurring_cycle',
        ]);

        $invoice = Invoice::create($input);

        if (count($inputInvoiceTaxes) > 0) {
            $invoice->invoiceTaxes()->sync($inputInvoiceTaxes);
        }

        DB::commit();


        if ($input['status'] != Invoice::DRAFT) {
            self::saveNotification($input, $invoice);
        }

        if ($invoice->status != Invoice::DRAFT) {
            $input['invoiceData'] = $invoice;
            $input['clientData'] = $invoice->client->user->toArray();
            if (getSettingValue('mail_notification')) {
                Mail::to($invoice->client->user->email)->send(new InvoiceCreateClientMail($input));
            }
        }

        return $invoice;
    }

    public function exceptionNotification($exception)
    {
        Notification::make()
            ->danger()
            ->title($exception->getMessage())
            ->send();
        $this->halt();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('messages.flash.invoice_saved_successfully');
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
}
