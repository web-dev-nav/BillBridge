<?php

namespace App\Installer\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Support\Facades\Hash;
use Shipu\WebInstaller\Concerns\StepContract;

class CustomApplicationFields implements StepContract
{
    public static function form(): array
    {
        $applicationFields = [];

        foreach (config('installer.applications', []) as $key => $value) {
            if ($key == 'admin.password') {
                $applicationFields[] = TextInput::make('applications.'.$key)
                    ->label($value['label'])
                    ->password()
                    ->maxLength(255)
                    ->default($value['default'])
                    ->dehydrateStateUsing(fn($state) => ! empty($state)
                        ? Hash::make($state) : "");
            } else {
                $applicationFields[] = TextInput::make('applications.'.$key)
                    ->label($value['label'])
                    ->required($value['required'])
                    ->rules($value['rules'])
                    ->default($value['default'] ?? '');
            }
        }

        return $applicationFields;
    }

    public static function make(): Step
    {
        return Step::make('application')
            ->label('Application Settings')
            ->description('Configure admin account and import database schema')
            ->schema(self::form());
    }

}