<?php

namespace App\Filament\Pages;

use App\Enums\{Permission};
use App\Forms\Components\MoneyInput;
use App\Helpers\AddressForm;
use App\Models\{Company};
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\{Form};
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\{Forms};
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class CompanyPage extends Page
{
    protected static string $view = 'filament.pages.company-page';

    public static function canAccess(): bool
    {
        /** @var User $user */
        $user = Auth::user(); //@phpstan-ignore-line

        return $user->hasAbility(Permission::ADMIN->value); //@phpstan-ignore-line
    }

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
                Forms\Components\Tabs::make('tabs')->columnSpanFull()->tabs([
                    Tab::make('Dados')->schema([
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
                                    ->preserveFilenames()
                                    ->directory('logo'),
                                Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->preserveFilenames()
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
                    Tab::make('Endereço')->schema([
                        AddressForm::setAddressFields(false),
                    ]),
                    Tab::make('Redes Sociais')->schema([
                        Forms\Components\TextInput::make('whatsapp')
                            ->label('Whatsapp')
                            ->maxLength(255)
                            ->prefixIcon('icon-whatsapp'),
                        Forms\Components\TextInput::make('x')
                            ->label('X')
                            ->maxLength(255)
                            ->prefixIcon('icon-twitter'),
                        Forms\Components\TextInput::make('instagram')
                            ->label('Instagram')
                            ->maxLength(255)
                            ->prefixIcon('icon-instagram'),
                        Forms\Components\TextInput::make('facebook')
                            ->label('Facebook')
                            ->maxLength(255)
                            ->prefixIcon('icon-facebook'),
                        Forms\Components\TextInput::make('linkedin')
                            ->label('Linkedin')
                            ->maxLength(255)
                            ->prefixIcon('icon-linkedin'),
                        Forms\Components\TextInput::make('youtube')
                            ->label('Youtube')
                            ->maxLength(255)
                            ->prefixIcon('icon-youtube'),
                    ])->columns(2),
                    Tab::make('Taxas')->schema([
                        MoneyInput::make('interest_rate_sale')
                            ->label('Interest rate sales')
                            ->prefix(null)
                            ->suffix('%'),
                        MoneyInput::make('interest_rate_installment')
                            ->label('Interest rate installment')
                            ->prefix(null)
                            ->suffix('%'),
                        MoneyInput::make('late_fee')->label('Late fee'),
                    ])->columns(3),
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Company::query()->first()->update($this->form->getState()); //@phpstan-ignore-line
        Notification::make()->body(__('Company updated successfully'))->icon('heroicon-o-check-circle')->iconColor('success')->send();
    }
}
