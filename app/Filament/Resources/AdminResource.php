<?php

namespace App\Filament\Resources;

use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use App\AdminDashboardSidebarSorting;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AdminResource\Pages;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class AdminResource extends Resource
{

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::ADMINS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.admins');
    }
    public static function getModelLabel(): string
    {
        return __('messages.admin');
    }

    public static function form(Form $form): Form
    {
        if ($form->getOperation() === 'edit') {
            $password = $form->model->password;
            $form->model = User::find($form->model->id);
            $form->model->password = $password;
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
                            ->live()
                            ->required(
                                function ($context, $get) {
                                    if ($context === 'create') {
                                        return __('messages.client.password');
                                    }else{
                                        return $get('password') || $get('password_confirmation') ? true : false;
                                    }
                                }
                            )
                            ->validationAttribute(__('messages.client.password'))
                            ->password()
                            ->maxLength(20),
                        TextInput::make('password_confirmation')
                            ->label(__('messages.client.confirm_password') . ':')
                            ->placeholder(__('messages.client.confirm_password'))
                            ->revealable()
                            ->live()
                            ->required(
                                function ($context, $get) {
                                    if ($context === 'create') {
                                        return __('messages.client.confirm_password');
                                    }else{
                                        return $get('password') || $get('password_confirmation') ? true : false;
                                    }
                                }
                            )
                            ->validationAttribute(__('messages.client.confirm_password'))
                            ->password()
                            ->maxLength(20),
                        SpatieMediaLibraryFileUpload::make('profile')
                            ->disk(config('app.media_disk'))
                            ->label(__('messages.client.profile') . ':')
                            ->avatar()
                            ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/jpg'])
                            ->collection(User::PROFILE),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('id', '!=', Auth::id())->whereHas('roles', fn($q) => $q->where('name', Role::ROLE_ADMIN))->with('media'))
            ->defaultSort('id', 'desc')
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection(User::PROFILE)
                    ->circular()
                    ->label(__('messages.common.name'))
                    ->width(50)
                    ->height(50)
                    ->sortable(['first_name'])
                    ->defaultImageUrl(function ($record) {
                        if (!$record->hasMedia(User::PROFILE)) {
                            return asset('images/avatar.png');
                        }
                    }),
                TextColumn::make('full_name')
                    ->label('')
                    ->description(function (User $record) {
                        return $record->email;
                    })
                    ->formatStateUsing(fn($record) => "<a href='" . self::getUrl('view', ['record' => $record->id]) . "'>" . $record->full_name ?? '' . "</a>")
                    ->html()
                    ->color('primary')
                    ->weight(FontWeight::SemiBold)
                    ->searchable(['first_name', 'last_name', 'email']),
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
                    ->using(function (User $record, $action) {
                        if ($record->is_default_admin) {
                            Notification::make()
                                ->danger()
                                ->title(__('messages.flash.Admin_cant_be_deleted'))
                                ->send();
                            $action->halt();
                        }

                        return $record->delete();
                    })
                    ->successNotificationTitle(__('messages.flash.Admin_deleted_successfully'))
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'view' => Pages\ViewAdmin::route('/{record}'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
