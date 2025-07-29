<?php

namespace App\Filament\Client\Pages;

use App\Repositories\DashboardRepository;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\View\View as ViewView;

class CurrencyReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.currency-report';
    protected static ?string $navigationLabel = null;
    public function getViewData(): array
    {
        $data = [];
        $dashboardRepository = app(DashboardRepository::class);
        $currencyData = $dashboardRepository->getAdminCurrencyData();
        $data['totalInvoices'] = $currencyData['totalInvoices'];
        $data['paidInvoices'] = $currencyData['paidInvoices'];
        $data['dueInvoices'] = $currencyData['dueInvoices'];
        $data['currencyDetails'] = $currencyData['currencyDetails'];

        return $data;
    }
}
