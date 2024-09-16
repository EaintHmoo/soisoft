<?php

namespace App\Filament\Buyer\Widgets;

use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;

class TandQChart extends ChartWidget
{
    protected static ?string $heading = 'Tender & Quotation by Status';

    protected static ?string $pollingInterval = null;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tender',
                    'data' => [5, 10, 7, 20],
                    'backgroundColor' => '#a5f3fc',
                    'borderColor' => '#a5f3fc',
                ],

                [
                    'label' => 'Quotation',
                    'data' => [20, 4, 30, 40],
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
