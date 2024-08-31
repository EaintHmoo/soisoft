<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Buyer\Document;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Models\Admin\PrePopulatedData;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\DocumentResource\Pages;
use App\Filament\Buyer\Resources\DocumentResource\RelationManagers;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('required_resubmit')
                    ->label('Required to response')
                    ->helperText('To be able to comparable, File must be excel file with redefined columns for both Questions and Answers.')
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Name of file')
                    ->placeholder('Placeholder')
                    ->required(),

                Select::make('document_type')
                    ->label('Tender Document Types')
                    ->required()
                    ->options(
                        PrePopulatedData::where('type', 'document_type')
                            ->get()
                            ->pluck('data.label', 'data.label')
                            ->toArray()
                    )
                    ->searchable(),
                
                FileUpload::make('document_path')
                    ->label('Tender Document')
                    ->required()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        'text/plain'
                    ])
                    ->helperText('Support Types: docx, xlsx, pdf, pptx, txt.')
                    ->directory('tender-documents')
                    ->columnSpanFull(),
                    
                Section::make('Document Config')
                    ->schema([
                        Toggle::make('comparable')
                            ->label('This Document is Comparable')
                            ->helperText('To be able to comparable, File must be excel file (.xlsx, .xls) with defined columns for both Questions and Answers.')
                            ->columnSpanFull(),

                        TextInput::make('question_columns')
                            ->label('Question Col Range')
                            ->placeholder('C8:C24'),
                        
                        TextInput::make('answer_columns')
                            ->label('Answer Col Range')
                            ->placeholder('D8:D24'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('File Name')
                    ->grow()
                    ->searchable()
                    ->sortable(),

                TextColumn::make('document_path')
                    ->label('Path')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('document_type')
                    ->label('Type')
                    ->grow()
                    ->searchable()
                    ->sortable(),
                IconColumn::make('required_resubmit')
                    ->label('Required Response')
                    ->boolean(),
                IconColumn::make('comparable')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->slideOver(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDocuments::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
