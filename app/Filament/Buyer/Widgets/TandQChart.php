<?php

namespace App\Filament\Buyer\Widgets;

use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use Filament\Widgets\ChartWidget;
use Filament\Support\Colors\Color;

class TandQChart extends ChartWidget
{
    protected static ?string $heading = 'Tender & Quotation by Status';

    protected static ?string $pollingInterval = null;
    
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        $status = [
            'draft',
            'review',
            'approved',
            'published'
        ];

        $tender_count = collect($status)->map(function ($status) {
            return Tender::where('tender_state', $status)->count();
        });

        $quotation_count = collect($status)->map(function ($status) {
            return Quotation::where('quotation_state', $status)->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Tender',
                    'data' => $tender_count,
                    'backgroundColor' => '#a5f3fc',
                    'borderColor' => '#a5f3fc',
                ],

                [
                    'label' => 'Quotation',
                    'data' => $quotation_count,
                    'backgroundColor' => '#ddd6fe',
                    'borderColor' => '#ddd6fe',
                ],
            ],
            'labels' => ['Draft', 'Review', 'Approved', 'Published'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
