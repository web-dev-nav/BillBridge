<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Payment;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class PaymentOverview extends ChartWidget
{
    protected static bool $isLazy = true;
    protected static ?int $sort = 3;
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
        return __('messages.admin_dashboard.payment_overview');
    }

    protected function getData(): array
    {
        $data = [];
        $data['received_amount'] = Payment::sum('amount');
        $data['invoice_amount'] = Invoice::where('status', '!=', Invoice::DRAFT)->sum('final_amount');
        $data['due_amount'] = $data['invoice_amount'] - $data['received_amount'];
        $data['labels'] = [
            __('messages.received_amount'),
            __('messages.invoice.due_amount'),
        ];
        $data['dataPoints'] = [$data['received_amount'], $data['due_amount']];
        return [
            'labels' => $data['labels'],
            'datasets' => [
                [
                    'borderColor' => [
                        '#4CAF50',
                        '#FF8A80',
                    ],
                    'data' => $data['dataPoints'],
                    'backgroundColor' => [
                        '#4CAF50',
                        '#FF8A80',
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
