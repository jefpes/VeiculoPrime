<?php

namespace App\Filament\Pages;

use App\Models\Company;
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
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make(5)->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->maxLength(50)
                            ->columnSpan(4),
                        Forms\Components\Toggle::make('navigation_mode')
                            ->label(function ($state) {
                                if ($state === false) {
                                    return __('Sidebar');
                                }

                                return __('Topbar');
                            })
                            ->inline(false)
                            ->extraAttributes([
                                'class' => 'my-2 size-xl',
                            ])
                            ->live(),
                    ]),
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\ColorPicker::make('primary_color')
                            ->label('Primary color'),
                        Forms\Components\ColorPicker::make('secondary_color')
                            ->label('Secondary color'),
                        Forms\Components\ColorPicker::make('tertiary_color')
                            ->label('Tertiary color'),
                        Forms\Components\ColorPicker::make('quaternary_color')
                            ->label('Quaternary color'),
                        Forms\Components\ColorPicker::make('quinary_color')
                            ->label('Quinary color'),
                        Forms\Components\ColorPicker::make('senary_color')
                            ->label('Senary color'),
                    ]),
                    Forms\Components\TextInput::make('cnpj')
                        ->label('CNPJ')
                        ->mask('99.999.999/9999-99')
                        ->rules(['required', 'size:18']),
                    Forms\Components\DatePicker::make('opened_in')
                        ->label('Opened in'),
                    Forms\Components\TextInput::make('address')
                        ->label('Address')
                        ->maxLength(255),
                    Forms\Components\Textarea::make('about')
                        ->label('About'),
                    Forms\Components\TextInput::make('phone')
                        ->label('Phone')
                        ->maxLength(20),
                    Forms\Components\TextInput::make('whatsapp')
                        ->label('Whatsapp')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord:true)
                        ->maxLength(255),
                    Forms\Components\FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->directory('logo'),
                    Forms\Components\FileUpload::make('favicon')
                        ->label('Favicon')
                        ->image()
                        ->directory('favicon'),
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
