<?php

use Shipu\WebInstaller\Forms\Fields\ApplicationFields;
use Shipu\WebInstaller\Forms\Fields\FolderPermissionStep;
use Shipu\WebInstaller\Forms\Fields\ServerRequirementFields;
use App\Installer\CustomInstallationManager;
use App\Installer\Forms\LicenseVerificationFields;
use App\Installer\Forms\CustomEnvironmentFields;

return [

    'name' => 'Laravel Web Installer',

    'user_model' => \App\Models\User::class,

    'steps' => [
        ServerRequirementFields::class,
        FolderPermissionStep::class,
        LicenseVerificationFields::class,
        CustomEnvironmentFields::class,
    ],

    'redirect_route' => "welcome",

    'installation_manager' => CustomInstallationManager::class,
    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'core'                 => [
        'minPhpVersion' => '8.1.0',
    ],

    /*
    |--------------------------------------------------------------------------
    | Php and Apache Requirements
    |--------------------------------------------------------------------------
    |
    | php extensions and apache modules requirements
    |
    */
    'requirements'         => [
        'php'    => [
            'openssl',
            'pdo',
            'mbstring',
            'tokenizer',
            'JSON',
            'cURL',
        ],
        'apache' => [
            'mod_rewrite',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions'          => [
        'storage/framework/' => '755',
        'storage/logs/'      => '755',
        'bootstrap/cache/'   => '755',
    ],

    /*
    |--------------------------------------------------------------------------
    | Environment Form
    |--------------------------------------------------------------------------
    |
    | environment form fields
    |
    */
    'environment'          => [
        'form' => [
            'app.name'          => [
                'label'      => 'App Name',
                'required'   => true,
                'rules'      => 'string|max:100',
                'env_key'    => 'APP_NAME',
                'config_key' => 'app.name',
            ],
            'app.url'           => [
                'label'      => 'App Url',
                'required'   => true,
                'rules'      => 'url',
                'env_key'    => 'APP_URL',
                'config_key' => 'app.url',
            ],
            'database.host'     => [
                'label'      => 'Database Host',
                'required'   => true,
                'rules'      => 'required|string|max:50',
                'env_key'    => 'DB_HOST',
                'config_key' => 'database.connections.mysql.host',
            ],
            'database.port'     => [
                'label'      => 'Database Port',
                'required'   => true,
                'rules'      => 'required|numeric|min:1|max:65535',
                'env_key'    => 'DB_PORT',
                'config_key' => 'database.connections.mysql.port',
            ],
            'database.name'     => [
                'label'      => 'Database Name',
                'required'   => true,
                'rules'      => 'required|string|max:50',
                'env_key'    => 'DB_DATABASE',
                'config_key' => 'database.connections.mysql.database',
            ],
            'database.username' => [
                'label'      => 'Database Username',
                'required'   => true,
                'rules'      => 'required|string|max:50',
                'env_key'    => 'DB_USERNAME',
                'config_key' => 'database.connections.mysql.username',
            ],
            'database.password' => [
                'label'      => 'Database Password',
                'required'   => false,
                'rules'      => 'nullable|string|max:50',
                'env_key'    => 'DB_PASSWORD',
                'config_key' => 'database.connections.mysql.password',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Admin User
    |--------------------------------------------------------------------------
    |
    | Default admin user created automatically during installation
    |
    */
    'default_admin' => [
        'first_name' => 'Super',
        'last_name' => 'Admin', 
        'email' => 'admin@billbridge.com',
        'password' => '12345678'
    ]
];