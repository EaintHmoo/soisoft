<?php

namespace App\Filament\Buyer\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use App\Models\Admin\Category;
use App\Models\Buyer\Quotation;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Awcodes\TableRepeater\Header;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Radio;
use App\Infolists\Components\Contact;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use App\Infolists\Components\Overview;
use App\Models\Admin\PrePopulatedData;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Pages\SubNavigationPosition;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Infolists\Components\DocumentList;
use Filament\Tables\Filters\TernaryFilter;
use App\Infolists\Components\QuotationItem;
use Filament\Forms\Components\CheckboxList;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use App\Infolists\Components\DescriptionList;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Infolists\Components\RepeatableEntry;
use Awcodes\TableRepeater\Components\TableRepeater;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Buyer\Resources\QuotationResource\Pages;
use Filament\Infolists\Components\Section as InfolistSection;
use App\Filament\Buyer\Resources\QuotationResource\RelationManagers;
use Filament\Forms\Components\Wizard;

class QuotationResource extends Resource
{
    protected static ?string $model = Quotation::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-plus';

    protected static ?string $navigationGroup = 'eTender';

    protected static ?int $navigationSort = 1;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Wizard\Step::make('General')
                        ->icon('heroicon-m-squares-2x2')
                        ->schema([
                            Select::make('quotation_type')
                                ->label('Quotation Type')
                                ->options(
                                    PrePopulatedData::where('type', 'type_of_sourcing')
                                        ->where('data->type', 'Quotation')
                                        ->get()
                                        ->pluck('data.label', 'data.label')
                                        ->toArray()
                                )
                                ->searchable()
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
                            
                            TextInput::make('reference_no')
                                ->placeholder('MOESCHETQ24003387')
                                ->required(),

                            TextInput::make('quotation_title')
                                ->placeholder('RFQ Title')
                                ->required()
                                ->columnSpanFull(),

                            Select::make('categories')
                                ->relationship(
                                    name: 'categories',
                                    titleAttribute: 'name', 
                                    modifyQueryUsing: function(Builder $query) {
                                        return $query->where('parent_id', '!=', -1);
                                    }
                                )
                                // ->options(function() {
                                //     $categories = Category::where('parent_id', '!=', -1)
                                //                         ->get()
                                //                         ->groupBy('parent.name');
                                //     $to_return = [];
                                    
                                //     foreach($categories as $parent => $child) {
                                //         $to_return[$parent] = $child->pluck('name', 'id')->toArray();
                                //     }

                                //     return $to_return;
                                // })
                                ->getSearchResultsUsing(function (string $search){
                                    return Category::where('parent_id', '!=', -1)
                                                        ->where('name', 'like', "%{$search}%")
                                                        ->pluck('name', 'id')
                                                        ->toArray();
                                })
                                ->preload()
                                ->multiple()
                                ->required()
                                ->columnSpanFull(),
                            
                            Grid::make(2)
                                ->schema([
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
                                ]),

                            Section::make('Sourcing Information') 
                                ->schema([
                                    Grid::make([
                                            'default' => 2,
                                            'xs' => 1
                                        ])
                                        ->schema([
                                            Select::make('evaluation_type')
                                                ->label('Evaluation Type')
                                                ->options(
                                                    PrePopulatedData::where('type', 'evaluation_type')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable()
                                                ->required(),
                                            
                                            Toggle::make('is_open_sourcing')
                                                ->label('Open Sourcing')
                                                ->onIcon('heroicon-m-check')
                                                ->offIcon('heroicon-m-x-mark')
                                                ->onColor(Color::Gray)
                                                ->default(true)
                                                ->inline(false)
                                                ->helperText('Turn off to add your selected bidders below')
                                                ->live()
                                        ]),

                                    Select::make('bidders')
                                        ->label('Select bidders')
                                        ->relationship(
                                            name: 'bidders', 
                                            titleAttribute: 'name',
                                            modifyQueryUsing: fn (Builder $query) => $query->role('supplier'),
                                        )
                                        ->preload()
                                        ->multiple()
                                        ->required()
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => ! $get('is_open_sourcing')),

                                    Select::make('project_id')
                                        ->relationship('project', 'name')
                                        ->searchable()
                                        ->preload()
                                        ->createOptionForm([
                                            TextInput::make('name')
                                                ->required()
                                                ->label('Project name')
                                                ->placeholder('Placeholder')
                                        ])
                                        ->createOptionModalHeading('Create new project'),

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

                                    Select::make('currency')
                                        ->label('Base Currency')
                                        ->options(
                                            PrePopulatedData::where('type', 'currency')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable()
                                        ->required()
                                ])
                                ->columns(3),

                            Section::make('Delivery Information') 
                                ->schema([
                                    TextInput::make('delivery_contact_person')
                                        ->label('Contact Person')
                                        ->placeholder('Placeholder')
                                        ->required(),

                                    Textarea::make('delivery_address')
                                        ->label('Delivery Address')
                                        ->placeholder('Placeholder')
                                        ->required(),

                                    Grid::make(2)
                                        ->schema([
                                            Radio::make('is_partial_delivery')
                                                ->label('Partial Delivery?')
                                                ->options([
                                                    true => 'Yes',
                                                    false => 'No'
                                                ])
                                                ->default(true)
                                                ->inline()
                                        ])
                                ]),
                            
                            Section::make('NDA') 
                                ->schema([
                                    Toggle::make('nda_required')
                                        ->label('Required NDA?')
                                        ->onIcon('heroicon-m-check')
                                        ->offIcon('heroicon-m-x-mark')
                                        ->onColor(Color::Gray)
                                        ->columnSpanFull()
                                        ->helperText('Upload the NDA file for supplier to download and sign')
                                        ->live(),

                                    FileUpload::make('nda_document')
                                        ->hiddenLabel()
                                        ->acceptedFileTypes(['application/pdf'])
                                        ->helperText('Prefer to upload your NDA Document in PDF Document Format.')
                                        ->columnSpanFull()
                                        ->visible(fn (Get $get): bool => $get('nda_required')),
                                ]),

                            Section::make('Briefing Information') 
                                ->schema([
                                    Toggle::make('briefing_information_required')
                                        ->label('Briefing Information Required?')
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
                        ])
                        ->columns(3),

                    Wizard\Step::make('Items')
                        ->icon('heroicon-m-list-bullet')
                        ->schema([
                            Repeater::make('quotationItems')
                                ->relationship()
                                ->hiddenLabel()
                                ->schema([
                                    Textarea::make('description')
                                        ->label('Description')
                                        ->placeholder('Description')
                                        ->columnSpanFull(),
                                    
                                    Grid::make(3)
                                        ->schema([
                                            Radio::make('type')
                                                ->label('Item Type?')
                                                ->options([
                                                    'goods' => 'Goods',
                                                    'services' => 'Services'
                                                ])
                                                ->default('goods')
                                                ->inline()
                                        ]),
                                    
                                    TextInput::make('quantity')
                                        ->required()
                                        ->placeholder('Quantity')
                                        ->numeric(),

                                    Select::make('uom')
                                        ->label('UOM')
                                        ->required()
                                        ->options(
                                            PrePopulatedData::where('type', 'uom')
                                                ->get()
                                                ->pluck('data.label', 'data.label')
                                                ->toArray()
                                        )
                                        ->searchable(),

                                    Select::make('category_id')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->required(),

                                    Grid::make(2)
                                        ->schema([
                                            Select::make('expected_delivery_date')
                                                ->label('Expected Delivery Date')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'expected_delivery_date')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('delivery_terms')
                                                ->label('Delivery Terms')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'delivery_term')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('payment_terms')
                                                ->label('Payment Terms')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'payment_term')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),

                                            Select::make('payment_mode')
                                                ->label('Payment Mode')
                                                ->required()
                                                ->options(
                                                    PrePopulatedData::where('type', 'payment_mode')
                                                        ->get()
                                                        ->pluck('data.label', 'data.label')
                                                        ->toArray()
                                                )
                                                ->searchable(),
                                        ]),

                                    Textarea::make('remark')
                                        ->label('Remark')
                                        ->placeholder('Placeholder')
                                        ->columnSpanFull(),

                                    Section::make('Cost Guide')
                                        ->schema([
                                            TextInput::make('company_estimated_unit_price')
                                                ->placeholder('000.00')
                                                ->numeric(),

                                            TextInput::make('historical_unit_price')
                                                ->placeholder('000.00')
                                                ->numeric(),
                                        ])
                                        ->columns(2),

                                    Section::make('Delivery Info')
                                        ->schema([
                                            Checkbox::make('same_as_header_address')
                                                ->default(true)
                                                ->live()
                                                ->columnSpanFull(),
                                            
                                            TextInput::make('delivery_contact_person')
                                                ->label('Contact Person Info')
                                                ->placeholder('Placeholder')
                                                ->disabled(fn (Get $get): bool => $get('same_as_header_address')),

                                            Textarea::make('delivery_address')
                                                ->placeholder('Placeholder')
                                                ->disabled(fn (Get $get): bool => $get('same_as_header_address')),
                                        ])
                                        ->columns(2)

                                ])
                                ->columns(3)
                                ->reorderable(false)
                                ->collapsed()
                                ->defaultItems(0)
                                ->addActionLabel('Add new')
                                ->itemLabel(fn (array $state): ?string => $state['description'] ?? null),
                        ]),

                    Wizard\Step::make('Contacts')
                        ->icon('heroicon-m-user-circle')
                        ->schema([
                            TableRepeater::make('quotationContacts')
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
                                        ->helperText('Support Types: docx, xlsx, pdf, pptx, txt.')
                                        ->directory('tender-documents')
                                        ->columnSpanFull(),

                                    Textarea::make('description')
                                        ->label('Description')
                                        ->placeholder('Placeholder')
                                        ->columnSpan(2),
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
                                    Radio::make('quotation_state')
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
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    TextColumn::make('reference_no')
                        ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class=" text-gray-400"> Quotation : '.$state.'</span>'))
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->searchable(),
                    
                    TextColumn::make('quotation_state')
                        ->badge()
                        ->alignEnd()
                        ->verticalAlignment(VerticalAlignment::Start)
                ]),

                Split::make([
                    TextColumn::make('quotation_title')
                        ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-lg"> '.$state.'</span>'))
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->searchable()
                ]),

                Split::make([
                    Stack::make([
                        TextColumn::make('created_at')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Published : </span> <span>'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start),
                        
                        TextColumn::make('categories.name')
                            ->separator(',')
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Categories : </span> <span>'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start)
                            ->searchable(),
                    ]),

                    Stack::make([
                        TextColumn::make('end_datetime')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Closing Date : </span> <span class="text-green-500">'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start),
                        
                        TextColumn::make('mode_of_submission')
                            ->since()
                            ->formatStateUsing(fn (string $state): HtmlString => new HtmlString('<span class="text-gray-400 text-xs"> Mode of Submission : </span> <span class="">'.$state.'</span>'))
                            ->verticalAlignment(VerticalAlignment::Start)
                    ])
                ]),
            ])
            ->filters([
                SelectFilter::make('categories')
                    ->label('Categories')
                    ->relationship('categories', 'name')
                    ->multiple()
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
                
                SelectFilter::make('mode_of_submission')
                    ->options(
                        PrePopulatedData::where('type', 'submission_mode')
                                    ->get()
                                    ->pluck('data.label', 'data.label')
                                    ->toArray()
                    )
                    ->searchable(),
                
                TernaryFilter::make('Sourcing')
                    ->placeholder('All')
                    ->trueLabel('Open sourcing')
                    ->falseLabel('Close sourcing')
                    ->queries(
                        true: fn (Builder $query) => $query->where('is_open_sourcing', true),
                        false: fn (Builder $query) => $query->where('is_open_sourcing', false),
                        blank: fn (Builder $query) => $query,
                    ),

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
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('quotation_title')
                    ->hiddenLabel()
                    ->size(TextEntry\TextEntrySize::Large)
                    ->weight(FontWeight::Bold),

                Overview::make('General Information')
                    ->label('General Information')
                    ->schema([
                        TextEntry::make('quotation_type')
                            ->label('Quotation Type')
                            ->view('infolists.components.custom-entry'),
                        
                        TextEntry::make('department.name')
                            ->label('Department')
                            ->view('infolists.components.custom-entry'),
                        
                        TextEntry::make('reference_no')
                            ->label('Reference No.')
                            ->view('infolists.components.custom-entry'),
                            
                        TextEntry::make('categories.name')
                            ->label('Categories.')
                            ->view('infolists.components.custom-entry')
                            ->listWithLineBreaks()
                            ->bulleted(),

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

                        TextEntry::make('is_open_sourcing')
                            ->label('Type of Sourcing')
                            ->view('infolists.components.custom-entry')
                            ->formatStateUsing(function (string $state) {
                                if($state) {
                                    return "Open";
                                }
                                return 'Closed';
                            })
                            ->badge(),

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

                Overview::make('Delivery Information')
                    ->label('Delivery Information')
                    ->schema([
                        TextEntry::make('delivery_contact_person')
                            ->label('Contact Person')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('delivery_address')
                            ->label('Delivery Address')
                            ->view('infolists.components.custom-entry'),

                        TextEntry::make('is_partial_delivery')
                            ->label('Partial Delivery?')
                            ->formatStateUsing(function (string $state) {
                                if($state) {
                                    return "Yes";
                                }
                                return 'No';
                            })
                            ->badge()
                            ->view('infolists.components.custom-entry'),
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

                Overview::make('Line Items')
                    ->label('Line Items')
                    ->schema([
                            QuotationItem::make('quotationItems')
                                ->hiddenLabel()
                        ]),

                Overview::make('Documents')
                    ->label('Documents')
                    ->schema([
                        DocumentList::make('documents')
                            ->hiddenLabel()
                    ])

            ])
            ->columns(1);
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
            'index' => Pages\ListQuotations::route('/'),
            'create' => Pages\CreateQuotation::route('/create'),
            'edit' => Pages\EditQuotation::route('/{record}/edit'),
            'view' => Pages\ViewQuotation::route('/{record}'),
            'bids' => Pages\ManageBids::route('/{record}/bids'),
            'awardings' => Pages\Awarding::route('/{record}/awardings'),
            'awardeds' => Pages\Awarded::route('/{record}/awardeds'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewQuotation::class,
            Pages\EditQuotation::class,
            Pages\ManageBids::class,
            Pages\Awarding::class,
            Pages\Awarded::class,
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
