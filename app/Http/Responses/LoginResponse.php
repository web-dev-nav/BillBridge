<?php

namespace App\Http\Responses;

use App\Models\Role;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        /** @var User $user */
        $user = auth()->user();

        if ($user) {
            $role = $user->roles()->first();
            if ($role && $role->name === Role::ROLE_ADMIN) {
                return redirect()->route('filament.admin.pages.dashboard');
            }

            if ($role && $role->name === Role::ROLE_CLIENT) {
                return redirect('/client');
            }
        }
    }
}
