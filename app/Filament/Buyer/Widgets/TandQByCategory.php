<?php

namespace App\Filament\Buyer\Widgets;

use App\Models\Admin\Category;
use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use Flowframe\Trend\Trend;
use Filament\Widgets\ChartWidget;

class TandQByCategory extends ChartWidget
{
    protected static ?string $heading = 'Tender and Quotation by Category';

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $categories = Category::where('parent_id', -1)->get();
        
        $tender_count = $categories->map(function ($category) {
            return Tender::where('category_id', $category->id)->count();
        });
        
        $quotation_count = $categories->map(function ($category) {
            $child_cats = $category->children()->pluck('id')->toArray();
            return Quotation::whereHas('categories', function($query) use ($child_cats) {
                $query->whereIn('id', $child_cats);
            })->count();
        });
        
        return [
            'datasets' => [
                [
                    'label' => 'Tender',
                    'data' => $tender_count,
                    'borderColor' => '#fecaca',
                ],

                [
                    'label' => 'Quotation',
                    'data' => $quotation_count,
                    'borderColor' => '#ddd6fe',
                ],
            ],
            'labels' => $categories->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
