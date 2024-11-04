<?php

namespace App\Filament\Buyer\Resources\TenderResource\Pages;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TenderQuestion;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Split;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Buyer\Resources\TenderResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageQuestion extends ManageRelatedRecords
{
    protected static string $resource = TenderResource::class;

    protected static string $relationship = 'questions';

    protected static ?string $navigationIcon = '';

    public static function getNavigationLabel(): string
    {
        return 'Questions';
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        if($parameters['record']['tender_state'] == 'published') {
            return true;
        }
        return false;
    }

    public function getTitle(): string
    {
        return 'Tender #' . $this->record->tender_no;
    }

    public function getSubheading(): ?string
    {
        return $this->record->department->name;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Textarea::make('answer')
                    ->placeholder('Write your answer')
                    ->rows(8)
                    ->required(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question')
            ->columns([
                Split::make([
                    Tables\Columns\TextColumn::make('question_by.name')
                            ->weight(FontWeight::Bold)
                            ->grow(false),
                    Tables\Columns\TextColumn::make('created_at')
                        ->size(Tables\Columns\TextColumn\TextColumnSize::ExtraSmall)
                        ->since()
                ]),
                Stack::make([
                    Tables\Columns\TextColumn::make('question')
                        ->weight(FontWeight::Medium),
                    Tables\Columns\TextColumn::make('answer')
                        ->extraAttributes([
                            'class' => 'bg-gray-50 dark:bg-gray-900 p-3 rounded-md mt-2'
                        ])
                        ->visible(fn($record) => null !== $record?->answer),
                ])
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->label('Reply')
                    ->icon('heroicon-m-arrow-uturn-right')
                    ->slideOver()
                    ->modalWidth(MaxWidth::Small),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ])
            ->defaultSort('created_at', 'desc');
            // ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('question_by_id')->whereNotNull('question'));
    }

    public static function getNavigationBadge(): ?string
    {
        return self::getResource()::getModel()::find(request()->route()->parameter('record'))?->questions()->count();
    }
}