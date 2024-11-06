<?php

namespace App\Filament\Pages;

use App\Models\Settings;
use Filament\Forms\{Form};
use Filament\Pages\Page;
use Filament\{Forms};
use Illuminate\Contracts\Support\Htmlable;

class SettingsPage extends Page
{
    protected static string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 4;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Settings::query()->first()->toArray()); //@phpstan-ignore-line
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Management');
    }

    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }

    public function getTitle(): string | Htmlable
    {
        return __('Settings');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->maxLength(50),
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
                Forms\Components\TextInput::make('cnpj')
                    ->label('CNPJ')
                    ->maxLength(20),
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
            ])->statePath('data');
    }

    public function save(): void
    {
        Settings::query()->first()->update($this->form->getState()); //@phpstan-ignore-line
    }
}
