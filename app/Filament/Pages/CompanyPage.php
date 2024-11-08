<?php

namespace App\Filament\Pages;

use App\Enums\States;
use App\Models\{City, Company};
use Filament\Forms\{Form};
use Filament\Pages\Page;
use Filament\{Forms};
use Illuminate\Contracts\Support\Htmlable;

class CompanyPage extends Page
{
    protected static string $view = 'filament.pages.company-page';

    protected static ?int $navigationSort = 4;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Company::query()->first()->toArray()); //@phpstan-ignore-line
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Company');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Company');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Name')
                        ->maxLength(50)
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->mask('99.999.999/9999-99')
                        ->rules(['required', 'size:18']),
                    Forms\Components\DatePicker::make('opened_in')
                        ->label('Opened in'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone')
                        ->mask('(99) 9999-9999')
                        ->maxLength(20)
                        ->prefixIcon('heroicon-m-phone'),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord:true)
                        ->maxLength(255)
                        ->prefixIcon('heroicon-m-envelope'),
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\FileUpload::make('logo')
                                ->label('Logo')
                                ->image()
                                ->directory('logo'),
                            Forms\Components\FileUpload::make('favicon')
                                ->label('Favicon')
                                ->image()
                                ->directory('favicon'),
                        ])
                        ->columns(2)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('about')
                        ->label('About')
                        ->maxLength(255)
                        ->rows(3)
                        ->columnSpanFull(),
                ])->columns(3),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('zip_code')
                    ->required()
                    ->mask('99999-999'),
                    Forms\Components\Select::make('state')
                        ->options(collect(States::cases())->mapWithKeys(fn (States $status) => [$status->value => $status->value, ])->toArray())
                        ->required()
                        ->searchable()
                        ->preload(),
                    Forms\Components\Select::make('city_id')
                        ->label('City')
                        ->options(City::query()->pluck('name', 'id')->toArray())
                        ->optionsLimit(5)
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('neighborhood')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('street')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('number')
                        ->integer()
                        ->minValue(0),
                    Forms\Components\Textarea::make('complement')
                            ->maxLength(255),
                ]),
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('whatsapp')
                        ->label('Whatsapp')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('x')
                        ->label('X')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('instagram')
                        ->label('Instagram')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('facebook')
                        ->label('Facebook')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('linkedin')
                        ->label('Linkedin')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('youtube')
                        ->label('Youtube')
                        ->maxLength(255),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Company::query()->first()->update($this->form->getState()); //@phpstan-ignore-line
    }
}
