<?php

namespace App\Filament\Pages;

use Livewire\Livewire;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\InvoiceSetting;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Split;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use App\Repositories\SettingRepository;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

use Filament\Notifications\Notification;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;

class InvoiceTemplates extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::INVOICE_TEMPLATES->value;

    protected static string $view = 'filament.pages.invoice-templates';

    public ?array $data = [];

    public $selectedTemplate = null;

    public $invoiceColor = null;

    public static function getNavigationLabel(): string
    {
        return __('messages.invoice_templates');
    }

    public function getTitle(): string
    {
        return __('messages.invoice_templates');
    }

    public function mount(): void
    {
        $this->data = Setting::where('key', 'default_invoice_template')->first()->toArray();
        $this->selectedTemplate = $this->data['value'] ?? 'defaultTemplate';
        $this->data['default_invoice_color'] = InvoiceSetting::where('key', $this->data['value'])->first()->template_color ?? '#000000';
        $this->invoiceColor = $this->data['default_invoice_color'];
        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make()
                        ->schema([
                            Select::make('value')
                                ->options(InvoiceSetting::pluck('template_name', 'key')->toArray())
                                ->label(__('messages.invoice_templates') . ':')
                                ->native(false)
                                ->extraAttributes([
                                    'style' => 'width: 300px;',
                                ])
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if($state == null) {
                                        return;
                                    }
                                    
                                    $this->selectedTemplate = $state;
                                    $this->invoiceColor = InvoiceSetting::where('key', $state)->first()->template_color ?? '#000000';
                                    $this->dispatch('updateColorPicker', color: $this->invoiceColor);
                                })
                                ->searchable(),
                            ViewField::make('color')
                                ->label(__('messages.setting.color') . ':')
                                ->live()
                                ->view('forms.components.color-picker'),
                            TextInput::make('default_invoice_color')->id('default_invoice_color')->extraAttributes([
                                'class' => 'hidden',
                            ])->hiddenLabel(),
                            Actions::make([
                                Action::make('Save')
                                    ->action(function ($state) {
                                        if ($state['value'] == null) {
                                            return Notification::make()->danger()->title(__('messages.flash.select_invoice_template'))->send();
                                        }
                                        $input = [
                                            'template' => $state['value'],
                                            'default_invoice_color' => $state['default_invoice_color']
                                        ];
                                        app(SettingRepository::class)->updateInvoiceSetting($input);

                                        Notification::make()->success()->title(__('messages.flash.invoice_template_updated_successfully'))->send();

                                        return redirect(route('filament.admin.pages.invoice-templates'));
                                    })
                            ])

                        ])->grow(false)->extraAttributes([
                            'style' => 'margin-right: 100px;',
                        ]),

                    Section::make()
                        ->schema([
                            ViewField::make('')
                                ->live()
                                ->view('forms.components.invoice-template')
                                ->viewData([
                                    'data' => $this->data,
                                    'invoiceTemplate' => $this->data['value'],
                                    'invColor' => InvoiceSetting::where('key', $this->data['value'])->first()->template_color ?? '' 
                                ])
                        ]),
                ]),
            ])->statePath('data');
    }
}
