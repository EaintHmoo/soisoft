<?php

namespace App\Filament\Buyer\Widgets;

use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Quotations', 20)
                ->description('4 created this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Tenders', 18)
                ->description('3 created this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Suppliers', 98)
                ->description('20 increase this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }

    private function getTenderCount($q = null) {
        $count = Tender::count();

        return $count;
    }

    private function getQuotationCount($q = null) {
        $count = Quotation::count();

        return $count;
    }
}
