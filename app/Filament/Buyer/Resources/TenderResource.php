<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Buyer\Tender;
use App\Models\Admin\Category;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use App\Models\Admin\TenderCategory;
use Filament\Forms\Components\Radio;
use App\Infolists\Components\Contact;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use App\Infolists\Components\Overview;
use App\Models\Admin\PrePopulatedData;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Infolists\Components\TenderItem;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Infolists\Components\DocumentList;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\TextEntry;
use App\Infolists\Components\DescriptionList;
use Filament\Forms\Components\DateTimePicker;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\TenderResource\Pages;
use Filament\Infolists\Components\Section as InfolistSection;
use App\Filament\Buyer\Resources\TenderResource\RelationManagers;
use Filament\Forms\Components\Wizard;

class TenderResource extends Resource
{
    protected static ?string $model = Tender::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?string $recordTitleAttribute = 'tender_title';

    protected static ?int $navigationSort = 2;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General')
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
                                    // modifyQueryUsing: fn (Builder $query) => $query->where('parent_id', -1)
                                )
                                ->live()
                                ->afterStateUpdated(function (Set $set) {
                                    $set('sub_category_id', null);
                                })
                                ->label('Tender Category')
                                // ->preload()
                                ->required()
                                ->searchable()
                                ->getSearchResultsUsing(function (string $search): array {
                                    $parent_ids = Category::query()
                                        ->where(function (Builder $builder) use ($search) {
                                            $searchString = "%$search%";
                                            $builder->where('name', 'like', $searchString);
                                        })
                                        ->where('parent_id', '!=', -1)
                                        ->pluck('parent_id');
                                    
                                    return Category::query()
                                        ->whereIn('id', $parent_ids)
                                        ->orWhere('name', 'like', "%$search%")
                                        ->where('parent_id', -1)
                                        ->pluck('name', 'id')
                                        ->toArray();
                                }),

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
                                    ->relationship(
                                        name: 'bidders', 
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query->role('supplier'),
                                    )
                                    ->preload()
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

                    Wizard\Step::make('Items')
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
                        
                    Wizard\Step::make('Contacts')
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

                    Wizard\Step::make('Documents')
                        ->icon('heroicon-m-paper-clip')
                        ->schema([
                            Repeater::make('documents')
                                ->relationship()
                                ->hiddenLabel()
                                ->schema([
                                    Toggle::make('required_resubmit')
                                                ->label('Required to response')
                                                ->helperText('To be able to comparable, File must be excel file with redefined columns for both Questions and Answers.')
                                                ->columnSpanFull(),

                                    TextInput::make('name')
                                        ->label('Name of file')
                                        ->live(onBlur: true)
                                        ->placeholder('Placeholder')
                                        ->required(),

                                    Select::make('document_type')
                                        ->label('Document Types')
                                        ->required()
                                        ->options(
                                            PrePopulatedData::where('type', 'document_type')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable(),
                                    
                                    FileUpload::make('document_path')
                                        ->label('Attach file')
                                        ->required()
                                        ->acceptedFileTypes([
                                            'application/pdf',
                                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                            'text/plain'
                                        ])
                                        ->helperText('Support Types: pdf, docx, xlsx, pptx, txt.')
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
                                ->columns(2)
                                ->grid(2)
                                ->reorderable(false)
                                ->collapsed()
                                ->defaultItems(0)
                                ->addActionLabel('Add new document')
                                ->itemLabel(fn (array $state): ?string => $state['name'] ?? null),
                        ]),

                    Wizard\Step::make('Checklist & State')
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
                ->persistStepInQueryString()
                ->skippable(fn(string $operation): bool => $operation === 'edit')
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tender_title')
                    ->label('Tender Title')
                    ->grow()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
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
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('department_id')
                    ->label('Department')
                    ->relationship('department', 'name')
                    ->preload()
                    ->searchable(),
                
                SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->preload()
                    ->searchable(),

                SelectFilter::make('evaluation_type')
                    ->options(
                        PrePopulatedData::where('type', 'evaluation_type')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),

                SelectFilter::make('type_of_sourcing')
                    ->options(
                        PrePopulatedData::where('type', 'type_of_sourcing')
                                    ->where('data->type', 'Tender')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),

                SelectFilter::make('mode_of_submission')
                    ->options(
                        PrePopulatedData::where('type', 'submission_mode')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),

                Filter::make('start_datetime')
                    ->label('Start date between')
                    ->form([
                        DatePicker::make('start_from'),
                        DatePicker::make('start_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_datetime', '>=', $date),
                            )
                            ->when(
                                $data['start_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_datetime', '<=', $date),
                            );
                    }),

                Filter::make('end_datetime')
                    ->label('End date between')
                    ->form([
                        DatePicker::make('end_from'),
                        DatePicker::make('end_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['end_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_datetime', '>=', $date),
                            )
                            ->when(
                                $data['end_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_datetime', '<=', $date),
                            );
                    }),

                Filter::make('nda_required')
                    ->label('NDA required')
                    ->toggle()
                    ->modifyFormFieldUsing(fn (Toggle $field) => $field->inline(false)),
            ])
            ->filtersFormWidth('4xl')
            ->filtersFormColumns(3)
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
                TextEntry::make('tender_title')
                    ->hiddenLabel()
                    ->size(TextEntry\TextEntrySize::Large)
                    ->weight(FontWeight::Bold),

                Overview::make('General Information')
                    ->label('General Information')
                    ->schema([
                        TextEntry::make('tender_no')
                            ->label('Tender No.')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('department.name')
                            ->label('Department')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('category.name')
                            ->label('Category')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('subCategory.name')
                            ->label('Sub Category')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('start_datetime')
                            ->label('Start Date and Time')
                            ->view('infolists.components.custom-entry')
                            ->dateTime(),
                        TextEntry::make('end_datetime')
                            ->label('End Date and Time')
                            ->view('infolists.components.custom-entry')
                            ->dateTime(),
                    ]),
                Overview::make('Sourcing Information')
                    ->label('Sourcing Information')
                    ->schema([
                        TextEntry::make('evaluation_type')
                            ->label('Evaluation Type')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('type_of_sourcing')
                            ->label('Type of Sourcing')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('project.name')
                            ->label('Sourcing For')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('mode_of_submission')
                            ->label('Model of Submission')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('currency')
                            ->label('Base Currency')
                            ->view('infolists.components.custom-entry'),
                        TextEntry::make('nda_document')
                            ->label('NDA Document')
                            ->view('infolists.components.custom-entry')
                            ->formatStateUsing(function (string $state): HtmlString {
                                $url = '<div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                    </svg>
                                    <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                        <a href="'. Storage::url($state) .'" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">'. $state  .'</a>
                                    </div></div>';

                                return new HtmlString($url);
                            }),
                    ]),
                    Overview::make('Contact Information')
                        ->label('Contact Information')
                        ->schema([
                            Contact::make('contacts')
                                ->label('Contact')
                                ->hiddenLabel(),
                        ]),

                    Overview::make('Briefing Information')
                        ->label('Briefing Information')
                        ->schema([
                            TextEntry::make('briefing_date')
                                ->label('Date')
                                ->view('infolists.components.custom-entry')
                                ->dateTime(),
                            TextEntry::make('briefing_venue')
                                ->label('Venue')
                                ->view('infolists.components.custom-entry'),
                            TextEntry::make('briefing_details')
                                ->label('Description')
                                ->view('infolists.components.custom-entry')
                                ->html(),
                            TextEntry::make('briefing_documents')
                                ->label('Documents')
                                ->view('infolists.components.custom-entry')
                                ->listWithLineBreaks()
                                ->formatStateUsing(function (string $state) {
                                    $url = '<div class="flex items-center"><svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z" clip-rule="evenodd" />
                                        </svg>
                                        <div class="ml-4 flex min-w-0 flex-1 gap-2">
                                            <a href="'. Storage::url($state) .'" class="font-medium text-indigo-400 hover:text-indigo-300" target="__blank">'. $state  .'</a>
                                        </div></div>';
    
                                    return new HtmlString($url);
                                }),
                        ]),

                    Overview::make('Fees Information')
                        ->label('Fees Information')
                        ->schema([
                            TextEntry::make('tender_fees')
                                ->label('Tender Fees')
                                ->view('infolists.components.custom-entry'),
                            TextEntry::make('tender_fees_information')
                                ->label('Description')
                                ->view('infolists.components.custom-entry')
                                ->html(),
                        ]),

                    Overview::make('Tender Items')
                        ->label('Tender Items')
                        ->schema([
                                TenderItem::make('tenderItems')
                                    ->hiddenLabel()
                            ]),

                    Overview::make('Documents')
                        ->label('Documents')
                        ->schema([
                            DocumentList::make('documents')
                                ->hiddenLabel()
                        ]),
                        
                    Overview::make('Others')
                        ->label('Others')
                        ->schema([
                            TextEntry::make('internal_details')
                                ->label('Internal Details')
                                ->view('infolists.components.custom-entry')
                                ->html(),

                            TextEntry::make('external_details')
                                ->label('External Details')
                                ->view('infolists.components.custom-entry')
                                ->html(),
                        ])
                    
            ])
            ->columns(1);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewTender::class,
            Pages\EditTender::class,
            Pages\ManageBids::class,
            Pages\Awarding::class,
            Pages\Awarded::class,
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
            'bids' => Pages\ManageBids::route('/{record}/bids'),
            'awardings' => Pages\Awarding::route('/{record}/awardings'),
            'awardeds' => Pages\Awarded::route('/{record}/awardeds'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}