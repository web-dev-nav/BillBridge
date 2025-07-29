<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use App\AdminDashboardSidebarSorting;

class Countries extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-s-globe-europe-africa';
    protected static ?int $navigationSort = AdminDashboardSidebarSorting::COUNTRIES->value;
    public static function getNavigationLabel(): string
    {
        return __('messages.countries');
    }
}
