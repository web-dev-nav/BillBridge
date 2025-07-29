<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Http\Controllers\AdminPaymentController;
use App\Models\Payment;
use App\Repositories\AdminPaymentRepository;
use Exception;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use Filament\Forms\Components\Builder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\IconPosition;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected  $i = 1;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('excel')
                    ->label(__('messages.invoice.excel_export'))
                    ->icon('heroicon-o-document-plus')
                    ->url(route('admin.paymentsExcel'), shouldOpenInNewTab: true),
                Action::make('excel')
                    ->label(__('messages.pdf_export'))
                    ->icon('heroicon-o-document-text')
                    ->url(route('admin.payments.pdf'), shouldOpenInNewTab: true)
            ])
                ->icon('heroicon-o-chevron-down')
                ->iconPosition(IconPosition::After)
                ->color('success')
                ->label(__('messages.common.export'))
                ->button(),
            CreateAction::make()->label(__('messages.payment.add_payment'))->modalWidth('3xl')->modalHeading(__('messages.payment.add_payment'))
                ->action(function (array $data) {
                    $input = $data;
                   
                    try {
                        $adminPaymentRepo = app(AdminPaymentRepository::class);
                        $adminPaymentRepo->store($input);

                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.payment_saved_successfully'))
                            ->send();
                    } catch (Exception $exception) {
                        Notification::make()
                            ->danger()
                            ->title($exception->getMessage())
                            ->send();
                        $this->halt();
                        return;
                    }
                })->createAnother(false),


        ];
    }
}
