<?php

namespace App\Filament\Client\Widgets;

use App\Models\Invoice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashbaordOverview extends BaseWidget
{
    protected static string $view = 'client.widgets.dashboard';

    public static function canView(): bool
    {
        return auth()->user()->hasRole('client');
    }
    protected function getViewData(): array
    {
        $user = getLogInUser();
        $invoice = Invoice::where('client_id', $user->client->id)->where('status', '!=', Invoice::DRAFT)->get();
        $totalInvoices = $invoice->count();
        $paidInvoices = $invoice->where('status', Invoice::PAID)->count();
        $unpaidInvoices = $invoice->where('status', Invoice::UNPAID)->count();


        return [
            'totalInvoices' => (formatTotalAmount($totalInvoices)),
            'paidInvoices' => formatTotalAmount($paidInvoices),
            'unpaidInvoices' => $unpaidInvoices,
        ];
    }
}
