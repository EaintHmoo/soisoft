<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Actions;
use Filament\Support\Colors\Color;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Buyer\Resources\TenderResource;
use App\Models\Buyer\Tender;

class ListTenders extends ListRecords
{
    protected static string $resource = TenderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Tender')
                ->icon('heroicon-m-document-plus'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->badge(Tender::count())
                ->badgeColor(Color::Rose),
            'draft' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tender_state', 'draft'))
                ->badge(Tender::where('tender_state', 'draft')->count())
                ->badgeColor(Color::Rose),
            'review' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tender_state', 'review'))
                ->badge(Tender::where('tender_state', 'review')->count())
                ->badgeColor(Color::Rose),
            'approved' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tender_state', 'approved'))
                ->badge(Tender::where('tender_state', 'approved')->count())
                ->badgeColor(Color::Rose),
            'published' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tender_state', 'published'))
                ->badge(Tender::where('tender_state', 'published')->count())
                ->badgeColor(Color::Rose),
        ];
    }
}
