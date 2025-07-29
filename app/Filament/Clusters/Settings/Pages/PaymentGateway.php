<?php

namespace App\Filament\Clusters\Settings\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use App\Repositories\paymentGatewayRepository;

class PaymentGateway extends Page
{

    protected static string $view = 'filament.clusters.settings.pages.payment-gateway';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Settings::class;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('messages.payment-gateway');
    }

    public function getTitle(): string|Htmlable
    {
        return __('messages.payment-gateway');
    }

    public function mount(): void
    {
        $data = app(paymentGatewayRepository::class)->edit();
        $this->data = $data;
        $this->form->fill($data);
    }

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        //stripe
                        TextInput::make('stripe_key')
                            ->placeholder(__('messages.setting.stripe_key'))
                            ->label(__('messages.setting.stripe_key') . ':'),
                        TextInput::make('stripe_secret')
                            ->placeholder(__('messages.setting.stripe_secret'))
                            ->label(__('messages.setting.stripe_secret') . ':'),
                        Toggle::make('stripe_enabled')
                            ->label(__('messages.setting.stripe')),

                        //paypal
                        TextInput::make('paypal_client_id')
                            ->placeholder(__('messages.setting.paypal_client_id'))
                            ->label(__('messages.setting.paypal_client_id') . ':'),
                        TextInput::make('paypal_secret')
                            ->placeholder(__('messages.setting.paypal_secret'))
                            ->label(__('messages.setting.paypal_secret') . ':'),
                        Toggle::make('paypal_enabled')
                            ->label(__('messages.setting.paypal')),

                        //razorpay
                        TextInput::make('razorpay_key')
                            ->placeholder(__('messages.setting.razorpay_key'))
                            ->label(__('messages.setting.razorpay_key') . ':'),
                        TextInput::make('razorpay_secret')
                            ->placeholder(__('messages.setting.razorpay_secret'))
                            ->label(__('messages.setting.razorpay_secret') . ':'),
                        Toggle::make('razorpay_enabled')
                            ->label(__('messages.setting.razorpay')),

                        //paystack
                        TextInput::make('paystack_key')
                            ->placeholder(__('messages.setting.paystack_key'))
                            ->label(__('messages.setting.paystack_key') . ':'),
                        TextInput::make('paystack_secret')
                            ->placeholder(__('messages.setting.paystack_secret'))
                            ->label(__('messages.setting.paystack_secret') . ':'),
                        Toggle::make('paystack_enabled')
                            ->label(__('messages.setting.paystack')),

                        //Mercadopago
                        TextInput::make('mercadopago_key')
                            ->placeholder(__('messages.setting.mercadopago_key'))
                            ->label(__('messages.setting.mercadopago_key') . ':'),
                        TextInput::make('mercadopago_secret')
                            ->placeholder(__('messages.setting.mercadopago_secret'))
                            ->label(__('messages.setting.mercadopago_secret') . ':'),
                        Toggle::make('mercadopago_enabled')
                            ->label(__('messages.setting.mercadopago'))


                    ])
                    ->statePath('data')
                    ->columns(3),
            ]);
    }

    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('messages.common.save'))
                ->submit('save'),
            Action::make('cancel')
                ->label(__('messages.common.cancel'))
                ->action('cancel')
                ->outlined(),
        ];
    }

    public function save()
    {
        $input = $this->form->getState();

        $valid = app(paymentGatewayRepository::class)->store($input['data']);

        if ($valid) {
            return Notification::make()
                ->success()
                ->title(__('messages.flash.setting_updated_successfully'))
                ->send();
        }
    }

    public function cancel()
    {
        return $this->redirect(route('filament.admin.settings.pages.payment-gateway'));
    }
}
