<?php

namespace App\Filament\Buyer\Widgets;

use Filament\Widgets\ChartWidget;

class TandQByCategory extends ChartWidget
{
    protected static ?string $heading = 'Tender and Quotation by Category';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Tender',
                    'data' => [5, 10, 7, 20, 33, 18],
                    'backgroundColor' => [
                        '#a5f3fc',
                        '#fed7aa',
                        '#ddd6fe',
                        '#fecaca',
                        '#bae6fd',
                        '#f5d0fe'
                    ],
                    'hoverOffset' => 4
                ],

                [
                    'label' => 'Quotation',
                    'data' => [3, 8, 5, 13, 27, 16],
                    'backgroundColor' => [
                        '#a5f3fc',
                        '#fed7aa',
                        '#ddd6fe',
                        '#fecaca',
                        '#bae6fd',
                        '#f5d0fe'
                    ],
                    'hoverOffset' => 4
                ],
            ],
            'labels' => [
                'ICT', 
                'Construction & Civil Engineering', 
                'Healthcare & Medical Supplies', 
                'Office Supplies & Services',
                'Professional Services',
                'Transportation & Logistics'
            ],
        ];
    }

    public function getDescription(): ?string
    {
        return 'Outer cycle is for the Tenders and Inner cycle is for the Quotations.';
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
