<?php

namespace App\Filament\Widgets;

use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;

class IncomeOverview extends ChartWidget
{
    protected static bool $isLazy = true;
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '400px';
    protected static ?int $sort = 2;
    public ?string $filter = 'this_month';
    public function getHeading(): string|Htmlable|null
    {
        return __('messages.admin_dashboard.income_overview');
    }
    public function totalFilterDay($startDate, $endDate): array
    {
        $payemnts = Payment::whereIsApproved(Payment::APPROVED)->whereBetween(
            'payment_date',
            [$startDate, $endDate]
        )->select(DB::raw('DATE(payment_date) as date'), DB::raw('SUM(amount) as total_income'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $transactionMap = [];
        foreach ($payemnts as $payemnt) {
            $transactionMap[$payemnt->date] = $payemnt->total_income;
        }


        $period = CarbonPeriod::create($startDate, $endDate);

        $dateArr = [];
        $income = [];
        foreach ($period as $date) {
            $dateKey = $date->format('Y-m-d');

            $dateArr[] = $date->format('d-m-y');
            $income[] = $transactionMap[$dateKey] ?? 0;
        }

        $data['days'] = $dateArr;
        $data['income'] = [
            'label' => trans('messages.form.income') . ' (' . getCurrencySymbol() . ')',
            'data' => $income,
            'borderWidth' => 1,

        ];

        return $data;
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $start_date = null;
        $end_date = null;

        if ($activeFilter == 'today') {
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
        } elseif ($activeFilter == 'yesterday') {
            $start_date = Carbon::yesterday()->format('Y-m-d');
            $end_date = Carbon::today()->format('Y-m-d');
        } elseif ($activeFilter == 'last_7_days') {
            $start_date = Carbon::now()->subDays(7)->format('Y-m-d');
            $end_date = Carbon::today()->format('Y-m-d');
        } elseif ($activeFilter == 'last_30_days') {
            $start_date = Carbon::now()->subDays(30)->format('Y-m-d');
            $end_date = Carbon::today()->format('Y-m-d');
        } elseif ($activeFilter == 'this_month') {
            $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
            $end_date = Carbon::today()->format('Y-m-d');
        } elseif ($activeFilter == 'last_month') {
            $start_date = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
            $end_date = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');
        }

        if ($start_date && $end_date) {
            $report = $this->totalFilterDay($start_date, $end_date);
        } else {
            $report = [
                'days' => [],
                'income' => [
                    'label' => 'Income',
                    'data' => [],
                ],
            ];
        }

        return [
            'datasets' => [$report['income']],
            'labels' => $report['days'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => __('messages.datepicker.today'),
            'yesterday' => __('messages.datepicker.yesterday'),
            'last_7_days' => __('messages.datepicker.last_7_days'),
            'last_30_days' => __('messages.datepicker.last_30_days'),
            'this_month' => __('messages.datepicker.this_month'),
            'last_month' => __('messages.datepicker.last_month'),
        ];
    }
}
