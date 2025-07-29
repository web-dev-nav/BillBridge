<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use App\AdminDashboardSidebarSorting;

class Settings extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = AdminDashboardSidebarSorting::SETTINGS->value;

    public static function getNavigationLabel(): string
    {
        return __('messages.settings');
    }

}
