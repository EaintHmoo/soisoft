<?php

namespace App\Filament\Buyer\Widgets;

use App\Models\Buyer\Quotation;
use App\Models\Buyer\Tender;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Quotations', $this->getQuotationCount())
                ->description($this->getQuotationCount(now()) . ' created this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Tenders', $this->getTenderCount())
                ->description($this->getTenderCount(now()) . ' created this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
            Stat::make('Suppliers', $this->getSupplierCount())
                ->description($this->getSupplierCount(now()) . ' increase this month')
                ->descriptionIcon('heroicon-m-arrow-trending-up'),
        ];
    }

    private function getTenderCount($month = null) {
        $count = Tender::count();
        if($month != null) {
            $count = Tender::whereMonth('created_at', $month)->count();
        }
        return $count;
    }

    private function getQuotationCount($month = null) {
        $count = Quotation::count();

        if($month != null) {
            $count = Quotation::whereMonth('created_at', $month)->count();
        }

        return $count;
    }

    private function getSupplierCount($month = null) {
        $count = User::role('supplier')->count();

        if($month != null) {
            $count = User::role('supplier')
                        ->whereMonth('created_at', $month)->count();
        }

        return $count;
    }
}
