<?php

namespace App\Installer\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\View;
use Shipu\WebInstaller\Concerns\StepContract;

class CustomEnvironmentFields implements StepContract
{
    public static function form(): array
    {
        $environmentFields = [];

        // Add database import progress indicator at the top
        $environmentFields[] = View::make('installer.import-progress')
            ->extraAttributes(['id' => 'import-progress-section']);

        foreach (config('installer.environment.form', []) as $key => $value) {
            $environmentFields[] = TextInput::make('environment.'.$key)
                ->label($value['label'])
                ->required($value['required'])
                ->rules($value['rules'])
                ->default($value['default'] ?? '');
        }

        return $environmentFields;
    }

    public static function make(): Step
    {
        return Step::make('environment')
            ->label('Environment Configuration')
            ->description('Configure database settings and import schema')
            ->schema(self::form())
            ->afterValidation(function ($state) {
                // Trigger database import after environment validation
                self::triggerDatabaseImport($state);
            });
    }

    private static function triggerDatabaseImport($state): void
    {
        // This will be handled by JavaScript on the frontend
        // The actual import happens in the CustomInstallationManager
    }
}