<?php

namespace App\Filament\Client\Resources;

use App\AdminDashboardSidebarSorting;
use App\Filament\Client\Resources\TransactionResource\Pages\ListTransactions;
use App\Filament\Resources\ClientResource;
use App\Models\Payment;
use App\Models\Role;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TransactionResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-s-numbered-list';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::TRANSACTIONS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.transactions');
    }

    public static function getModelLabel(): string
    {
        return __('messages.transactions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                if (getLogInUser()->hasRole(Role::CLIENT)) {
                    $query->whereHas('invoice.client', function ($q) {
                        $q->where('user_id', Auth::id());
                    });
                }
            })
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('transaction_id')
                    ->label(__('messages.payment.transaction_id'))
                    ->default(__('messages.common.n/a'))
                    ->hidden(getLogInUser()->hasrole(Role::CLIENT))
                    ->searchable(),
                TextColumn::make('invoice.invoice_id')
                    ->label(__('messages.invoice.invoice_id'))
                    ->badge('info')
                    ->url(fn($record) => (InvoiceResource::getUrl('view', ['record' => $record->invoice->id])))
                    ->searchable(),
                TextColumn::make('invoice.client.user.full_name')
                    ->label(__('messages.invoice.client'))
                    ->color('primary')
                    ->html()
                    ->formatStateUsing(fn($record) => "<a href='" . ClientResource::getUrl('view', ['record' => $record->invoice->client->id]) . "' class='text-indigo-600 hover:underline'>" . e($record->invoice->client->user->full_name) . "</a>")
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name'])
                    ->description(fn($record) => $record->invoice->client->user->email ?? __('messages.common.n/a'))
                    ->hidden(getLogInUser()->hasrole(Role::CLIENT)),
                TextColumn::make('payment_date')
                    ->label(__('messages.payment.payment_date'))
                    ->badge('primary')
                    ->date('Y-m-d')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('amount')
                    ->label(__('messages.quote.amount'))
                    ->formatStateUsing(function ($record) {
                        return getInvoiceCurrencyAmount($record->amount, $record->invoice->currency_id, true);
                    })
                    ->sortable()
                    ->searchable(),
                TextColumn::make('is_approved')
                    ->label(__('messages.setting.payment_approved'))
                    ->view('filament.clusters.transactions.columns.is_approved')
                    ->hidden(getLogInUser()->hasrole(Role::CLIENT)),
                TextColumn::make('payment_mode')
                    ->label(__('messages.payment.payment_mode'))
                    ->formatStateUsing(function ($record) {
                        return Payment::PAYMENT_MODE[$record->payment_mode];
                    })
                    ->badge('info')
                    ->description(fn($record) => view('filament.clusters.transactions.columns.note_modal', ['record' => $record])),
                TextColumn::make('user_id')
                    ->label(__('messages.common.status'))
                    ->formatStateUsing(function ($record) {
                        return Payment::PAYMENT_MODE[$record->payment_mode];
                    })
                    ->view('filament.clusters.transactions.columns.status')
                    ->badge('info'),
                TextColumn::make('payment_attachment')
                    ->label(__('messages.common.attachment'))
                    ->html()
                    ->default(__('messages.common.n/a'))
                    ->view('filament.clusters.transactions.columns.attachment')
                // ->getStateUsing(
                //     function ($record) {
                //         $attachment = $record->getMedia(Payment::PAYMENT_ATTACHMENT)->first()?->getUrl();
                //         // dump($attachment);
                //         // if (getLogInUser()->hasrole(Role::CLIENT)) {
                //         //     if ($record->invoice->client->user_id !== getLogInUserId()) {
                //         //         Notification::make()
                //         //             ->danger()
                //         //             ->title(__('messages.flash.seems_you_are_not_allowed_to_access_this_record'))
                //         //             ->send();
                //         //         $this->halt();
                //         //         return;
                //         //     }
                //         // }

                //         if ($attachment) {
                //             return '<a href="' . $attachment . '" style="margin-left: -17px;color: #6571ff" class="hoverLink  " target="_blank" download>Download</a>';
                //         } else {
                //             return '';
                //         }
                //     }
                // )

            ])
            ->filters([
                SelectFilter::make('payment_mode')
                    ->label(__('messages.payment.payment_mode'))
                    ->options([Payment::PAYMENT_MODE_NEW])
                    ->native(false),
                SelectFilter::make('is_approved')
                    ->label(__('messages.common.status'))
                    ->options([Payment::PAYMENT_STATUS_NEW])
                    ->native(false),
            ])
            ->actions([])
            ->paginated([10, 25, 50]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTransactions::route('/'),
        ];
    }
}
