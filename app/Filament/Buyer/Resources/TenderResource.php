<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Buyer\Tender;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use App\Models\Admin\TenderCategory;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\TenderResource\Pages;
use App\Filament\Buyer\Resources\TenderResource\RelationManagers;
use App\Models\Admin\PrePopulatedData;
use Filament\Support\Enums\FontWeight;
use Awcodes\TableRepeater\Components\TableRepeater;
use Awcodes\TableRepeater\Header;


class TenderResource extends Resource
{
    protected static ?string $model = Tender::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?string $recordTitleAttribute = 'tender_title';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('General Information')
                            ->icon('heroicon-m-squares-2x2')
                            ->schema([
                                TextInput::make('tender_no')
                                    ->placeholder('LTA00ETT24000051')
                                    ->required(),
                                Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Department name')
                                            ->placeholder('Placeholder')
                                    ])
                                    ->createOptionModalHeading('Create new department'),
                                TextInput::make('tender_title')
                                    ->required()
                                    ->columnSpanFull()
                                    ->placeholder('Placeholder'),
                                    
                                Select::make('category_id') 
                                    ->relationship(
                                        name: 'category', 
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->where('parent_id', -1)
                                    )
                                    ->live()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('sub_category_id', null);
                                    })
                                    ->label('Tender Category')
                                    ->preload()
                                    ->required()
                                    ->searchable(),

                                Select::make('sub_category_id') //category data from admin dashboard
                                    ->label('Tender Sub Category')
                                    ->relationship(
                                        name: 'subCategory', 
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query, Get $get) => $query->where('parent_id', $get('category_id'))
                                    )
                                    ->preload()
                                    ->searchable(),

                                Select::make('project_id') //data source ?
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Internal use only')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('Project name')
                                            ->placeholder('Placeholder')
                                    ])
                                    ->createOptionModalHeading('Create new project')
                                    ->columnSpanFull(),

                                DateTimePicker::make('start_datetime')
                                    ->label('Start Date and Time')
                                    ->helperText('The default timezone is Cambodia (GMT+7)')
                                    ->required()
                                    ->placeholder('Jul 27, 2024 13:02:00')
                                    ->native(false),
                                
                                DateTimePicker::make('end_datetime')
                                    ->label('End Date and Time')
                                    ->required()
                                    ->placeholder('Aug 28, 2024 12:00:00')
                                    ->native(false),

                                Select::make('evaluation_type')
                                    ->label('Evaluation Type')
                                    ->options(
                                        PrePopulatedData::where('type', 'evaluation_type')
                                            ->get()
                                            ->pluck('data.label', 'data.label')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->helperText('Internal use only')
                                    ->required(),

                                Select::make('type_of_sourcing')
                                    ->label('Type of Sourcing/Tenders')
                                    ->options(
                                        PrePopulatedData::where('type', 'type_of_sourcing')
                                            ->where('data->type', 'Tender')
                                            ->get()
                                            ->pluck('data.label', 'data.label')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->live()
                                    ->required(),

                                Section::make([
                                    Select::make('bidders')
                                        ->label('Bidder Details')
                                        ->options([
                                            'microsoft' => 'Microsoft',
                                            'spacex' => 'SpaceX',
                                            'amazon' => 'Amazon',
                                            'google' => 'Google',
                                        ])
                                        ->multiple()
                                        ->required()
                                        ->columnSpanFull()
                                ])->visible(fn (Get $get): bool => $get('type_of_sourcing') == 'Closed/Selective Tender'),
                                
                                Select::make('currency') // data source ?
                                    ->label('Tender Currency')
                                    ->options(
                                        PrePopulatedData::where('type', 'currency')
                                            ->get()
                                            ->pluck('data.label', 'data.label')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->required()
                                    ->helperText('Exchange rates will be based on the day of submission'),
                                
                                Select::make('mode_of_submission') 
                                    ->label('Mode of Submission')
                                    ->options(
                                        PrePopulatedData::where('type', 'submission_mode')
                                            ->get()
                                            ->pluck('data.label', 'data.label')
                                            ->toArray()
                                    )
                                    ->searchable()
                                    ->required(),

                                Section::make('NDA') 
                                    ->schema([
                                        Toggle::make('nda_required')
                                            ->label('NDA required?')
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark')
                                            ->onColor(Color::Gray)
                                            ->columnSpanFull()
                                            ->live(),

                                        FileUpload::make('nda_document')
                                            ->label('NDA Document')
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->helperText('Prefer to upload your NDA Document in PDF Document Format.')
                                            ->columnSpanFull()
                                            ->visible(fn (Get $get): bool => $get('nda_required')),
                                    ]),
                                
                                Section::make('Tender Briefing Information') 
                                    ->schema([
                                        Toggle::make('briefing_information_required')
                                            ->label('Tender Briefing Information Required?')
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark')
                                            ->onColor(Color::Gray)
                                            ->columnSpanFull()
                                            ->live(),

                                        DatePicker::make('briefing_date')
                                            ->placeholder('Jul 27, 2024')
                                            ->required()
                                            ->native(false)
                                            ->visible(fn (Get $get): bool => $get('briefing_information_required')),
                                        TextInput::make('briefing_venue')
                                            ->required()
                                            ->placeholder('Venue Name')
                                            ->visible(fn (Get $get): bool => $get('briefing_information_required')),
                                        RichEditor::make('briefing_details')
                                            ->required()
                                            ->placeholder('Briefing details')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                            ->columnSpanFull()
                                            ->visible(fn (Get $get): bool => $get('briefing_information_required')),
                                        FileUpload::make('briefing_documents')
                                            ->label('Briefing Documents')
                                            ->multiple()
                                            ->columnSpanFull()
                                            ->acceptedFileTypes(['application/pdf'])
                                            ->helperText('Prefer to upload your NDA Document in PDF Document Format.')
                                            ->visible(fn (Get $get): bool => $get('briefing_information_required')),
                                    ])
                                    ->columns(2)
                                    ->collapsible(),
                                
                                Section::make('Tender Fees') 
                                    ->schema([
                                        Toggle::make('fees_required')
                                            ->label('Required Tender Fees to submit?')
                                            ->onIcon('heroicon-m-check')
                                            ->offIcon('heroicon-m-x-mark')
                                            ->onColor(Color::Gray)
                                            ->columnSpanFull()
                                            ->live(),

                                        TextInput::make('tender_fees')
                                            ->required()
                                            ->label('Tender Fees')
                                            ->numeric()
                                            ->prefixIcon('heroicon-m-currency-dollar')
                                            ->visible(fn (Get $get): bool => $get('fees_required')),
                                        RichEditor::make('tender_fees_information')
                                            ->required()
                                            ->placeholder('Tender Fees Information')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                            ->visible(fn (Get $get): bool => $get('fees_required')),
                                        // FileUpload::make('fees_documents')
                                        //     ->label('Tender Fees Documents')
                                        //     ->multiple()
                                        //     ->acceptedFileTypes(['application/pdf'])
                                        //     ->helperText('Prefer to upload your documents in PDF Document Format.')
                                        //     ->visible(fn (Get $get): bool => $get('fees_required')),
                                    ])
                                    ->collapsible(),
                                
                                Section::make('Additional Tender Information (Internal View)') 
                                    ->schema([
                                        RichEditor::make('internal_details')
                                            ->hiddenLabel()
                                            ->placeholder('Additional Tender Information')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                    ])
                                    ->collapsed(),
                                Section::make('External Additional Tender Information (Outside Bidder can view)') 
                                    ->schema([
                                        RichEditor::make('external_details')
                                            ->hiddenLabel()
                                            ->placeholder('Additional Tender Information')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                    ])
                                    ->collapsed(),
                            ])->columns(2),

                        Tabs\Tab::make('Items')
                            ->icon('heroicon-m-list-bullet')
                            ->schema([
                                Repeater::make('tenderItems')
                                    ->relationship()
                                    ->hiddenLabel()
                                    ->schema([
                                        TextInput::make('part_number')
                                            ->required()
                                            ->label('Material Part No')
                                            ->placeholder('Placeholder')
                                            ->columnSpan(2),
                                        
                                        Select::make('uom')
                                            ->label('UOM')
                                            ->required()
                                            ->options(
                                                PrePopulatedData::where('type', 'uom')
                                                    ->get()
                                                    ->pluck('data.label', 'data.label')
                                                    ->toArray()
                                            )
                                            ->searchable()
                                            ->columnSpan(2),

                                        TextInput::make('estimate_quantity')
                                            ->required()
                                            ->placeholder('Placeholder')
                                            ->numeric()
                                            ->columnSpan(2),
                                        
                                        TextInput::make('specifications')
                                            ->required()
                                            ->columnSpanFull()
                                            ->live(onBlur: true)
                                            ->placeholder('Placeholder'),

                                        RichEditor::make('description')
                                            ->label('Item Description')
                                            ->placeholder('Description')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                            ->columnSpan(3),
                                        
                                        RichEditor::make('notes_to_supplier')
                                            ->label('Note to Supplier')
                                            ->placeholder('Note to supplier')
                                            ->disableToolbarButtons([
                                                'strike',
                                                'codeBlock',
                                                'attachFiles'
                                            ])
                                            ->columnSpan(3)
                                    ])
                                    ->columns(6)
                                    ->reorderable(false)
                                    ->collapsed()
                                    ->defaultItems(0)
                                    ->addActionLabel('Add new')
                                    ->itemLabel(fn (array $state): ?string => $state['specifications'] ?? null),
                            ])
                            ->columns(1),
                        
                        Tabs\Tab::make('Contact Details')
                            ->icon('heroicon-m-user-circle')
                            ->schema([
                                TableRepeater::make('tenderContacts')
                                    ->relationship()
                                    ->headers([
                                        Header::make('Contact Person'),
                                    ])
                                    ->schema([
                                        Select::make('contact_id')
                                            ->relationship('contact')
                                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                                $label = "<p> {$record->contact_person} </p>";
                                                if($record->designation) $label .= "<span class='text-xs text-slate-500'>- {$record->designation}</span><br>";
                                                if($record->phone) $label .= "<span class='text-xs text-slate-500'>- {$record->phone}</span><br>";
                                                if($record->email) $label .= "<span class='text-xs text-slate-500'>- {$record->email}</span><br>";

                                                return $label;
                                            })
                                            ->allowHtml()
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Section::make([
                                                    TextInput::make('contact_person')
                                                        ->required()
                                                        ->label('Contact Person')
                                                        ->placeholder('Elon Musk')
                                                        ->prefixIcon('heroicon-m-user'),
                                                    TextInput::make('designation')
                                                        ->required()
                                                        ->label('Designation')
                                                        ->placeholder('CEO')
                                                        ->prefixIcon('heroicon-m-briefcase'),
                                                    TextInput::make('phone')
                                                        ->label('Phone Number')
                                                        ->placeholder('+66 111222333')
                                                        ->tel()
                                                        ->prefixIcon('heroicon-m-phone'),
                                                    TextInput::make('email')
                                                        ->label('Email Address')
                                                        ->placeholder('elon@spacex.com')
                                                        ->email()
                                                        ->prefixIcon('heroicon-m-at-symbol'),
                                                    RichEditor::make('address')
                                                        ->label('Address')
                                                        ->disableToolbarButtons([
                                                            'strike',
                                                            'codeBlock',
                                                            'attachFiles'
                                                        ])
                                                        ->columnSpanFull()
                                                ])
                                                ->columns(2)
                                            ])
                                            ->createOptionModalHeading('Create new contact'),
                                    ])
                                    ->columnSpan('full')
                                    ->addActionLabel('Add contact')
                            ]),

                        Tabs\Tab::make('Tender Documents')
                            ->icon('heroicon-m-paper-clip')
                            ->schema([
                                TableRepeater::make('tenderDocuments')
                                    ->relationship()
                                    ->headers([
                                        Header::make('Document Name'),
                                    ])
                                    ->schema([
                                        Select::make('document_id')
                                            ->relationship('document')
                                            ->getOptionLabelFromRecordUsing(function (Model $record) {
                                                $label = "<p> {$record->name} </p>";
                                                if($record->document_type) $label .= "<p class='indent-3 text-xs text-slate-500'>Type - {$record->document_type}</p>";

                                                
                                                if($record->required_resubmit) {
                                                    $label .= "<p class='indent-3 text-xs text-slate-500'>Re-submit - Yes </p>";
                                                } else {
                                                    $label .= "<p class='indent-3 text-xs text-slate-500'>Re-submit - No </p>";
                                                }

                                                if($record->Comparable) {
                                                    $label .= "<p class='indent-3 text-xs text-slate-500'>Comparable - Yes </p>";
                                                } else {
                                                    $label .= "<p class='indent-3 text-xs text-slate-500'>Comparable - No </p>";
                                                }

                                                return $label;
                                            })
                                            ->allowHtml()
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->createOptionForm([
                                                Toggle::make('required_resubmit')
                                                    ->label('Required to response')
                                                    ->helperText('To be able to comparable, File must be excel file with redefined columns for both Questions and Answers.')
                                                    ->columnSpanFull(),

                                                Section::make([
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
                                                        ->searchable()
                                                ])
                                                ->columns(2),
                                                
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
                                            ])
                                            ->createOptionModalHeading('Create new document'),
                                    ])
                                    ->columnSpan('full')
                                    ->addActionLabel('Add document')
                            ]),

                        Tabs\Tab::make('Checklist & State')
                            ->icon('heroicon-m-clipboard-document-list')
                            ->schema([
                                Section::make('Publication Check List')
                                    ->schema([
                                        CheckboxList::make('publication_check_list')
                                                ->hiddenLabel()
                                                ->required()
                                                ->options([
                                                    'clearly_defined' => 'All requirements are clearly defined',
                                                    'documents_are_completed' => 'All tender documents are completed and checked in to the system',
                                                    'contract_terms_and_conditions' => 'All contract terms & conditions and contract compliance statements',
                                                    'rfp_terms_and_conditions' => 'All RFP terms & conditions and connected party dicisions',
                                                ])
                                    ])
                                    ->columnSpan(1),

                                Section::make('Tender State')
                                    ->schema([
                                        Radio::make('tender_state')
                                            ->hiddenLabel()
                                            ->options([
                                                'draft' => 'Draft for Review',
                                                'review' => 'Review for Approve',
                                                'approved' => 'Approved for Publish',
                                                'published' => 'Publish'
                                            ])
                                            ->default('draft')
                                    ])
                                    ->columnSpan(1)
                            ])->columns(2)
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tender_title')
                    ->label('Tender Title')
                    ->grow()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                TextColumn::make('start_datetime')
                    ->label('Start Date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_datetime')
                    ->label('End Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make([
                    TextEntry::make('tender_no')
                        ->size(TextEntry\TextEntrySize::Large)
                        ->weight(FontWeight::Bold),
                    TextEntry::make('tender_title')
                        ->size(TextEntry\TextEntrySize::Large)
                ])
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenders::route('/'),
            'create' => Pages\CreateTender::route('/create'),
            'edit' => Pages\EditTender::route('/{record}/edit'),
            'view' => Pages\ViewTender::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}