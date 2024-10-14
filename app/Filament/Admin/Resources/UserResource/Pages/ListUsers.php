<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Admin\Resources\UserResource;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array {
        return [
            'Buyers' => Components\Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => 
                        $query->whereHas('roles', function($query) {
                                $query->where('name', 'buyer');
                            })
                    )
                    ->badge(User::query()
                        ->whereHas('roles', function($query) {
                            $query->where('name', 'buyer');
                        })
                        ->count()
                    ),

            'Suppliers' => Components\Tab::make()
                    ->modifyQueryUsing(fn (Builder $query) => 
                        $query->whereHas('roles', function($query) {
                                $query->where('name', 'supplier');
                            })
                    )
                    ->badge(User::query()
                        ->whereHas('roles', function($query) {
                            $query->where('name', 'supplier');
                        })
                        ->count()
                    ),
        ];
    }

}
