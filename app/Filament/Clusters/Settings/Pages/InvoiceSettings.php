<?php

namespace App\Filament\Clusters\Settings\Pages;

use App\Models\Currency;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Filament\Clusters\Settings;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use App\Repositories\SettingRepository;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\ToggleButtons;

class InvoiceSettings extends Page
{

    protected static string $view = 'filament.clusters.settings.pages.invoice-settings';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $cluster = Settings::class;

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function getNavigationLabel(): string
    {
        return __('messages.setting.invoice_settings');
    }

    public function getTitle(): string|Htmlable
    {
        return __('messages.setting.invoice_settings');
    }

    public function mount(): void
    {
        $data = app(SettingRepository::class)->getSyncList();
        $this->data = $data;
        $this->form->fill($data);
    }

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('current_currency')
                            ->options(Currency::all()->pluck('name', 'id'))
                            ->required()
                            ->validationAttribute(__('messages.setting.currencies'))
                            ->searchable()
                            ->label(__('messages.setting.currencies') . ':'),
                        Toggle::make('currency_after_amount')
                            ->required()
                            ->label(__('messages.setting.currency_position') . ':')
                            ->validationAttribute(__('messages.setting.currency_position'))
                            ->inline(false)
                            ->helperText(__('messages.setting.show_currency_behind')),
                        TextInput::make('invoice_no_prefix')
                            ->label(__('messages.setting.invoice_no_prefix') . ':')
                            ->placeholder(__('messages.setting.invoice_no_prefix')),
                        ToggleButtons::make('decimal_separator')
                            ->options([
                                '.' => 'DOT(.)',
                                ',' => 'COMMA(,)',
                            ])
                            ->reactive()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('thousand_separator', $state === '.' ? ',' : '.')
                            )
                            ->required()
                            ->label(__('messages.setting.decimal_separator') . ':')
                            ->validationAttribute(__('messages.setting.decimal_separator'))
                            ->inline(),
                        TextInput::make('invoice_no_suffix')
                            ->label(__('messages.setting.invoice_no_suffix') . ':')
                            ->placeholder(__('messages.setting.invoice_no_suffix')),
                        ToggleButtons::make('thousand_separator')
                            ->options([
                                '.' => 'DOT(.)',
                                ',' => 'COMMA(,)',
                            ])
                            ->reactive()
                            ->required()
                            ->afterStateUpdated(
                                fn($state, callable $set) =>
                                $set('decimal_separator', $state === '.' ? ',' : '.')
                            )
                            ->label(__('messages.setting.thousand_separator') . ':')
                            ->validationAttribute(__('messages.setting.thousand_separator'))
                            ->inline(),
                        Toggle::make('show_product_description')
                            ->label(__('messages.setting.show_product_description') . ':')
                            ->inline(false),
                        TextInput::make('due_invoice_days')
                            ->label(__('messages.setting.send_due_invoice_email_before_x_days') . ':')
                            ->placeholder(__('messages.setting.send_due_invoice_email_before_x_days')),
                        Toggle::make('send_whatsapp_invoice')
                            ->label(__('Send WhatsApp Invoice') . ':')
                            ->live()
                            ->reactive()
                            ->inline(false),
                        Group::make()
                            ->columnSpan(2)
                            ->schema([
                                TextInput::make('twilio_sid')
                                    ->placeholder(__('Twilio SID'))
                                    ->required()
                                    ->validationAttribute(__('Twilio SID'))
                                    ->label(__('Twilio SID') . ':'),
                                TextInput::make('twilio_token')
                                    ->placeholder(__('Twilio Token'))
                                    ->required()
                                    ->validationAttribute(__('Twilio Token'))
                                    ->label(__('Twilio Token') . ':'),
                                TextInput::make('twilio_from_number')
                                    ->placeholder(__('Twilio From'))
                                    ->required()
                                    ->validationAttribute(__('Twilio From'))
                                    ->label(__('Twilio From') . ':'),
                            ])->columns(2)->visible(function (callable $get) {
                                if ($get('send_whatsapp_invoice')) {
                                    return true;
                                }
                                return false;
                            }),
                    ])->columns(2),
            ])->statePath('data');
    }
    public function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('messages.common.save'))
                ->submit('save'),
            Action::make('cancel')
                ->color('primary')
                ->label(__('messages.common.cancel'))
                ->action('cancel')
                ->outlined(),

        ];
    }

    public function save()
    {
        $input = $this->form->getState();

        $valid = app(SettingRepository::class)->updateSetting($input);

        if ($valid) {
            return Notification::make()
                ->success()
                ->title(__('messages.flash.setting_updated_successfully'))
                ->send();
        }
    }

    public function cancel()
    {
        return $this->redirect(route('filament.admin.settings.pages.invoice-settings'));
    }
}
