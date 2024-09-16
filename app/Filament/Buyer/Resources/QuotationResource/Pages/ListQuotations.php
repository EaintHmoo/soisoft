<?php

namespace App\Filament\Buyer\Resources\QuotationResource\Pages;

use Filament\Actions;
use App\Models\Buyer\Quotation;
use Filament\Support\Colors\Color;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Buyer\Resources\QuotationResource;

class ListQuotations extends ListRecords
{
    protected static string $resource = QuotationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Quotation::count())
                ->badgeColor(Color::Rose),
            'draft' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quotation_state', 'draft'))
                ->badge(Quotation::where('quotation_state', 'draft')->count())
                ->badgeColor(Color::Rose),
            'review' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quotation_state', 'review'))
                ->badge(Quotation::where('quotation_state', 'review')->count())
                ->badgeColor(Color::Rose),
            'approved' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quotation_state', 'approved'))
                ->badge(Quotation::where('quotation_state', 'approved')->count())
                ->badgeColor(Color::Rose),
            'published' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('quotation_state', 'published'))
                ->badge(Quotation::where('quotation_state', 'published')->count())
                ->badgeColor(Color::Rose),
        ];
    }
}
