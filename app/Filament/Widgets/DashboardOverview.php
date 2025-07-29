<?php

namespace App\Filament\Widgets;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardOverview extends BaseWidget
{

    protected static string $view = 'filament.widgets.dashboard';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('admin');
    }
    protected function getViewData(): array
    {
        $invoice = Invoice::toBase()->get();
        $totalInvoices = $invoice->count();
        $totalClients = Client::count();
        $totalProducts = Product::count();
        $paidInvoices = $invoice->where('status', Invoice::PAID)->count();
        $unpaidInvoices = $invoice->where('status', Invoice::UNPAID)->count();


        return [
            'totalInvoices' => (formatTotalAmount($totalInvoices)),
            'totalClients' => formatTotalAmount($totalClients),
            'totalProducts' => formatTotalAmount($totalProducts),
            'paidInvoices' => formatTotalAmount($paidInvoices),
            'unpaidInvoices' => $unpaidInvoices,
        ];
    }
}
