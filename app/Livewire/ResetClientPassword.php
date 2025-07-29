<?php

namespace App\Livewire;

use Exception;
use Filament\Panel;
use App\Models\User;
use App\Models\Client;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Illuminate\Http\Request;
use Filament\Facades\Filament;
use Filament\Pages\SimplePage;
use Livewire\Attributes\Locked;
use Filament\Actions\ActionGroup;
use Filament\Support\Colors\Color;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Contracts\Support\Htmlable;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Illuminate\Validation\Rules\Password as PasswordRule;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class ResetClientPassword extends SimplePage
{
    use InteractsWithFormActions;
    use WithRateLimiting;

    protected static string $view = 'filament-panels::pages.auth.password-reset.reset-password';

    #[Locked]
    public ?string $email = null;

    public ?string $password = '';

    public ?string $passwordConfirmation = '';

    #[Locked]
    public ?string $token = null;

    public function mount(Request $request): void
    {
        try {
            $id =  Crypt::decrypt($request->id);
            $user = User::with('client')->where('id', $id)->first();
            $this->email = $user->email;
            $this->token = Password::broker('users')->createToken($user);

            $is_password_set = $user->client->is_password_set;

            if ($is_password_set != 0) {
                redirect(route('filament.admin.auth.login'));
            }
        } catch (Exception $e) {
            redirect(route('filament.admin.auth.login'));
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        $data = $this->form->getState();

        $request->validate([
            'password' => ['password', 'passwordConfirmation', PasswordRule::default()],
        ]);

        // Get the user by email
        $user = User::where('email', $this->email)->firstOrFail();

        // Update the user's password directly
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        // Mark password as set for the client
        Client::where('user_id', $user->id)->update([
            'is_password_set' => 1,
        ]);

        // Send success notification
        Notification::make()
            ->title(__('Password reset successfully!'))
            ->success()
            ->send();

        return redirect()->route('filament.admin.auth.login'); // Redirect to login
    }


    protected function getRateLimitedNotification(TooManyRequestsException $exception): ?Notification
    {
        return Notification::make()
            ->title(__('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.title', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]))
            ->body(array_key_exists('body', __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/password-reset/reset-password.notifications.throttled.body', [
                'seconds' => $exception->secondsUntilAvailable,
                'minutes' => $exception->minutesUntilAvailable,
            ]) : null)
            ->danger();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.email.label'))
            ->disabled()
            ->extraAttributes([
                'class' => 'fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 ring-gray-950/10 dark:ring-white/20 fi-fo-text-input overflow-hidden',
                'style' => "--tw-ring-color: #6571ff;",
            ])
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password.label'))
            ->password()
            ->extraAttributes([
                'class' => 'fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 ring-gray-950/10 dark:ring-white/20 fi-fo-text-input overflow-hidden',
                'style' => "--tw-ring-color: #6571ff;",
            ])
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(PasswordRule::default())
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/password-reset/reset-password.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.password_confirmation.label'))
            ->password()
             ->extraAttributes([
                'class' => 'fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 ring-gray-950/10 dark:ring-white/20 fi-fo-text-input overflow-hidden',
                'style' => "--tw-ring-color: #6571ff;",
            ])
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    public function getTitle(): string | Htmlable
    {
        return __('filament-panels::pages/auth/password-reset/reset-password.title');
    }

    public function getHeading(): string | Htmlable
    {
        return __('filament-panels::pages/auth/password-reset/reset-password.heading');
    }

    /**
     * @return array<Action | ActionGroup>
     */
    protected function getFormActions(): array
    {
        return [
            $this->getResetPasswordFormAction(),
        ];
    }

    public function getResetPasswordFormAction(): Action
    {
        return Action::make('resetPassword')
            ->color(Color::hex('#6571ff'))
            ->label(__('filament-panels::pages/auth/password-reset/reset-password.form.actions.reset.label'))
            ->submit('resetPassword');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }
}
