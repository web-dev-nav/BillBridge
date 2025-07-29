<?php

namespace App\Filament\Resources\PaymentQrCodeResource\Pages;

use App\Filament\Resources\PaymentQrCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePaymentQrCodes extends ManageRecords
{
    protected static string $resource = PaymentQrCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->successNotificationTitle(__('messages.flash.payment_qr_code_saved_successfully'))->modalWidth("md")->createAnother(false),
        ];
    }
}
