<?php

namespace App\Filament\Admin\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables\Grouping\Group;
use App\Models\Admin\Category;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Filament\Admin\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Select parent category')
                    ->relationship(
                        name: 'parent', 
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query->where('parent_id', -1),
                    )
                    // ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name} ({$record->key})")
                    ->searchable()
                    ->preload()
                    ->native(false),
                
                // Forms\Components\TextInput::make('key')
                //     ->placeholder('Parent Key')
                //     ->unique(
                //         table: Category::class, 
                //         column: 'key', 
                //         ignoreRecord: true
                //     ),

                Forms\Components\TextInput::make('name')
                    ->placeholder('TV & Home Appliances')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $old, ?string $state) {
                        if (($get('slug') ?? '') !== Str::slug($old)) {
                            return;
                        }
                    
                        $set('slug', Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->placeholder('tv-and-home-appliances')
                    ->required()
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->groups([
                Group::make('parent.id')
                    ->titlePrefixedWithLabel(false)
                    ->getTitleFromRecordUsing(function (Category $record) {
                        if(!is_null($record->parent)) return "{$record->parent->name}";

                        return 'Parent Categories';
                    }),
            ])
            ->defaultGroup('parent.id')
            ->columns([
                // Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('name')
                    ->grow()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('parent.key'),
            ])
            ->filters([
                SelectFilter::make('parent_id')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCategories::route('/'),
        ];
    }
}
