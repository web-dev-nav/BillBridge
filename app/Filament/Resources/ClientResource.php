<?php

namespace App\Filament\Resources;

use App\Models\City;
use App\Models\User;
use Filament\Tables;
use App\Models\State;
use App\Models\Client;
use App\Models\Country;
use App\Models\Invoice;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Filament\Resources\Resource;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Group;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\ClientResource\Pages;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::CLIENTS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.clients');
    }
    public static function getModelLabel(): string
    {
        return __('messages.client.client');
    }

    public static function form(Form $form): Form
    {
        if ($form->getOperation() === 'edit') {
            $id = $form->model->user_id;
            $form->model = User::find($id);
        }
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('first_name')
                            ->label(__('messages.client.first_name') . ':')
                            ->placeholder(__('messages.client.first_name'))
                            ->validationAttribute(__('messages.client.first_name'))
                            ->maxValue(255)
                            ->required(),
                        TextInput::make('last_name')
                            ->label(__('messages.client.last_name') . ':')
                            ->placeholder(__('messages.client.last_name'))
                            ->validationAttribute(__('messages.client.last_name'))
                            ->maxValue(255)
                            ->required(),
                        TextInput::make('email')
                            ->label(__('messages.client.email') . ':')
                            ->placeholder(__('messages.client.email'))
                            ->required()
                            ->validationAttribute(__('messages.client.email'))
                            ->unique('users', 'email', ignoreRecord: true)
                            ->email(),
                        PhoneInput::make('contact')
                            ->defaultCountry('IN')
                            ->countryStatePath('region_code')
                            ->rules(function ($get) {
                                return [
                                    'required',
                                    'phone:AUTO,' . strtoupper($get('prefix_code')),
                                ];
                            })
                            ->validationMessages([
                                'phone' => __('messages.placeholder.invalid_number'),
                            ])
                            ->label(__('messages.client.contact_no') . ':')
                            ->validationAttribute(__('messages.client.contact_no'))
                            ->required(),
                        TextInput::make('password')
                            ->revealable()
                            ->rules(['min:6', 'max:20'])
                            ->label(__('messages.client.password') . ':')
                            ->placeholder(__('messages.client.password'))
                            ->confirmed()
                            ->required(fn($record, $get) => is_null($record) || $get('password'))
                            ->validationAttribute(__('messages.client.password'))
                            ->password()
                            ->live()
                            ->reactive()
                            ->maxLength(20),
                        TextInput::make('password_confirmation')
                            ->label(__('messages.client.confirm_password') . ':')
                            ->placeholder(__('messages.client.confirm_password'))
                            ->revealable()
                            ->required(fn($record, $get) => is_null($record) || $get('password'))
                            ->requiredWith('password')
                            ->validationAttribute(__('messages.client.confirm_password'))
                            ->password()
                            ->maxLength(20),
                        TextInput::make('website')
                            ->label(__('messages.client.website') . ':')
                            ->placeholder(__('messages.client.website'))
                            ->required()
                            ->validationAttribute(__('messages.client.website')),
                        TextInput::make('postal_code')
                            ->label(__('messages.client.postal_code') . ':')
                            ->placeholder(__('messages.client.postal_code'))
                            ->required()
                            ->validationAttribute(__('messages.client.postal_code')),
                        Group::make([
                            Group::make([
                                Select::make('country_id')
                                    ->label(__('messages.client.country') . ':')
                                    ->options(Country::all()->pluck('name', 'id'))
                                    ->native(false)
                                    ->preload()
                                    ->optionsLimit(count(Country::all()))
                                    ->afterStateUpdated(function ($set, $get) {
                                        $set('city_id', null);
                                        $set('state_id', null);
                                        $set('state_id', State::whereCountryId($get('country_id'))->get()->value('id'));
                                        $set('city_id', City::whereStateId($get('state_id'))->get()->value('id'));
                                        $set('edit_city', false);
                                    })
                                    ->columnSpan(6)
                                    ->live()
                                    ->searchable(),
                                Select::make('state_id')
                                    ->label(__('messages.client.state') . ':')
                                    ->native(false)
                                    ->searchable()
                                    ->live()
                                    ->columnSpan(6)
                                    ->options(function ($get) {
                                        return State::where('country_id', $get('country_id'))->pluck('name', 'id');
                                    })
                                    ->afterStateUpdated(function ($set, $get) {
                                        $set('city_id', null);
                                        $set('city_id', City::whereStateId($get('state_id'))->get()->value('id'));
                                        $set('edit_city', false);
                                    }),
                                Select::make('city_id')
                                    ->label(__('messages.client.city') . ':')
                                    ->native(false)
                                    ->searchable()
                                    ->columnSpan(6)
                                    ->live()
                                    ->visible(fn($get, $record) => !$get('edit_city') || !empty($record->note))
                                    ->options(function ($get) {
                                        return City::where('state_id', $get('state_id'))->pluck('name', 'id');
                                    }),
                                TextInput::make('city_name')
                                    ->columnSpan(6)
                                    ->label(__('messages.client.city') . ':')
                                    ->placeholder(__('messages.client.city'))
                                    ->visible(fn($get, $record) => $get('edit_city') || !empty($record->note)),
                                Actions::make([
                                    Action::make('add_new_city')
                                        ->iconButton()
                                        ->icon('heroicon-s-pencil-square')
                                        ->hiddenLabel()
                                        ->extraAttributes(['style' => 'padding-top: 55px;'])
                                        ->action(function ($set) {
                                            $set('edit_city', true);
                                        })
                                ])
                                    ->columnSpan(1)
                            ])->columns(19)
                        ])->columnSpanFull(),
                        Textarea::make('address')
                            ->rows(4)
                            ->placeholder(__('messages.client.address'))
                            ->label(__('messages.client.address') . ':'),
                        Textarea::make('note')
                            ->rows(4)
                            ->placeholder(__('messages.client.notes'))
                            ->label(__('messages.client.notes') . ':'),
                        Group::make([
                            Group::make([
                                TextInput::make('company_name')
                                    ->placeholder(__('messages.setting.company_name'))
                                    ->label(__('messages.setting.company_name') . ':'),
                                SpatieMediaLibraryFileUpload::make('user.profile')
                                    ->disk(config('app.media_disk'))
                                    ->label(__('messages.client.profile') . ':')
                                    ->avatar()
                                    ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                                    ->collection(User::PROFILE),
                                TextInput::make('vat_no')
                                    ->placeholder(__(getVatNoLabel()))
                                    ->label(__(getVatNoLabel()) . ':'),
                            ])->columns(3)
                        ])->columnSpanFull()
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('user.profile')
                    ->collection(User::PROFILE)
                    ->circular()
                    ->label(__('messages.client.client'))
                    ->width(50)
                    ->height(50)
                    ->sortable(['first_name'])
                    ->defaultImageUrl(function ($record) {
                        if (!$record->user->hasMedia(User::PROFILE)) {
                            return asset('images/avatar.png');
                        }
                    }),
                TextColumn::make('user.full_name')
                    ->label('')
                    ->description(fn($record) => $record->user->email)
                    ->color('primary')
                    ->formatStateUsing(fn($record) => "<a href='" . self::getUrl('view', ['record' => $record->id]) . "'>" . $record->user->full_name . "</a>")
                    ->html()
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name', 'email']),
                TextColumn::make('id')
                    ->badge()
                    ->label(__('messages.common.invoice'))
                    ->color('success')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn($record) => $record->invoices()->count()),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->actionsColumnLabel(__('messages.common.actions'))
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->using(function ($record, Request $request, $action) {
                        $check = $request->get('clientWithInvoices');

                        $invoiceModels = [
                            Invoice::class,
                        ];

                        $result = canDelete($invoiceModels, 'client_id', $record->id);

                        if ($check && $result) {
                            Notification::make()
                                ->danger()
                                ->title(__('messages.flash.client_cant_deleted'))
                                ->send();
                            $action->halt();
                        }

                        $record->user()->delete();
                        $record->invoices()->delete();

                        return $record->delete();
                    })
                    ->successNotificationTitle(__('messages.flash.client_deleted_successfully'))
                    ->iconButton(),
            ])
            ->bulkActions([])
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'view' => Pages\ViewClient::route('/{record}'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
