<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PaymentQrCode;
use Filament\Resources\Resource;
use App\AdminDashboardSidebarSorting;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PaymentQrCodeResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\PaymentQrCodeResource\RelationManagers;
use App\Models\Invoice;

class PaymentQrCodeResource extends Resource
{
    protected static ?string $model = PaymentQrCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?int $navigationSort =  AdminDashboardSidebarSorting::PAYMENT_QR_CODES->value;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->placeholder(__('messages.payment_qr_codes.title'))
                    ->label(__('messages.payment_qr_codes.title') . ':')
                    ->validationAttribute(__('messages.payment_qr_codes.title'))
                    ->maxLength(255),
                SpatieMediaLibraryFileUpload::make('image')
                    ->label(__('messages.payment_qr_codes.qr_image') . ':')
                    ->collection(PaymentQrCode::PAYMENT_QR_CODE)
                    ->disk(config('app.media_disk'))
                    ->avatar()
                    ->panelAspectRatio(null)
                    ->imageEditor()
                    ->required(),
            ])->columns(1);
    }

    public static function getLabel(): ?string
    {
        return __('messages.payment_qr_codes.payment_qr_codes');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->sortable()
                    ->searchable(),

                SpatieMediaLibraryImageColumn::make('avatar')->collection(PaymentQrCode::PAYMENT_QR_CODE)->label(__('messages.payment_qr_codes.qr_image'))->width(50)->height(50),

                Tables\Columns\ToggleColumn::make('is_default')
                    ->alignRight()
                    ->label(__('messages.payment_qr_codes.default'))
                    ->updateStateUsing(function ($record, $state) {
                        $isDefault = PaymentQrCode::where('is_default', '=', '1')->first();

                        if ($isDefault) {
                            $isDefault->is_default = 0;
                            $isDefault->save();
                        }

                        $state ? $record->is_default = 1 : $record->is_default = 0;

                        Notification::make()
                            ->title(__('messages.flash.payment_qr_code_status_updated_successfully'))
                            ->success()
                            ->send();

                        return $record->save();
                    }),

            ])
            ->filters([
                //
            ])
            ->recordAction(null)
            ->recordUrl(false)
            ->actions([
                Tables\Actions\EditAction::make()->successNotificationTitle(__('messages.flash.payment_qr_code_updated_successfully'))->modalWidth("md")->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->iconButton()
                    ->action(function (PaymentQrCode $record) {
                        $invoices = Invoice::where('payment_qr_code_id', '=', $record->id)->count();

                        if ($invoices > 0) {
                            Notification::make()->danger()->title(__('messages.flash.payment_qr_code_can_not_deleted'))->send();
                            return;
                        }
                        $record->delete();
                        return Notification::make()
                            ->success()
                            ->title(__('messages.flash.payment_qr_code_deleted_successfully'))
                            ->send();
                    }),
            ])->actionsColumnLabel(__('messages.common.action'))
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePaymentQrCodes::route('/'),
        ];
    }
}
