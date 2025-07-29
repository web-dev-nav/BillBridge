<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class InvoiceOverview extends ChartWidget
{
    protected static ?int $sort = 4;
    protected static ?array $options = [
        'scales' => [
            'x' => [
                'display' => false,
            ],
            'y' => [
                'display' => false,
            ],
        ],
        'animation' => [
            'animateRotate' => true,  // Enables rotation animation
            'animateScale' => true,   // Enables scaling animation
            'duration' => 1000,       // Animation duration (2 seconds)
        ],
    ];
    protected static ?string $maxHeight = '377px';

    public function getHeading(): string|Htmlable|null
    {
        return __('messages.admin_dashboard.invoice_overview');
    }


    protected function getData(): array
    {
        $data = [];
        $invoice = Invoice::toBase()->get();
        $data['total_paid_invoices'] = $invoice->where('status', Invoice::PAID)->count();
        $data['total_unpaid_invoices'] = $invoice->where('status', Invoice::UNPAID)->count();
        $data['labels'] = [
            __('messages.paid_invoices'),
            __('messages.unpaid_invoices'),
        ];
        $data['dataPoints'] = [$data['total_paid_invoices'], $data['total_unpaid_invoices']];
        return [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'borderColor' => [
                        '#4D96FF',
                        '#FFB74D',
                    ],
                    'data' => $data['dataPoints'],
                    'backgroundColor' => [
                        '#4D96FF',
                        '#FFB74D',
                    ],
                ]
            ],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
