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
                            ->columnSpan(2)
                            ->required(),
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
                    Tab::make('Home Page')->schema([
                        Forms\Components\Select::make('font')
                        ->allowHtml()
                        ->options([
                            "font-family:Times New Roman, Times, serif"     => "<span style='font-family:Times New Roman, Times, serif'>Times New Roman</span>",
                            'font-family:Roboto, sans-serif'                => "<span style='font-family:Roboto, sans-serif'>Roboto</span>",
                            'font-family:Arial, sans-serif'                 => "<span style='font-family:Arial, sans-serif'>Arial</span>",
                            'font-family:Courier New, Courier, monospace'   => "<span style='font-family:Courier New, Courier, monospace'>Courier New</span>",
                            'font-family:Georgia, serif'                    => "<span style='font-family:Georgia, serif'>Georgia</span>",
                            'font-family:Lucida Console, Monaco, monospace' => "<span style='font-family:Lucida Console, Monaco, monospace'>Lucida Console</span>",
                            'font-family:Tahoma, Geneva, sans-serif'        => "<span style='font-family:Tahoma, Geneva, sans-serif'>Tahoma</span>",
                            'font-family:Trebuchet MS, sans-serif'          => "<span style='font-family:Trebuchet MS, sans-serif'>Trebuchet MS</span>",
                            'font-family:Verdana, Geneva, sans-serif'       => "<span style='font-family:Verdana, Geneva, sans-serif'>Verdana</span>",
                            'font-family:Open Sans, sans-serif'             => "<span style='font-family:Open Sans, sans-serif'>Open Sans</span>",
                            'font-family:Inter, sans-serif'                 => "<span style='font-family:Inter, sans-serif'>Inter</span>",
                        ])
                        ->native(false),
                        Forms\Components\ColorPicker::make('font_color')
                            ->label('Font color'),
                        Forms\Components\ColorPicker::make('body_bg_color')
                            ->label('Body background color'),
                        Forms\Components\ColorPicker::make('nav_color')
                            ->label('Heading background color'),
                        Forms\Components\ColorPicker::make('footer_color')
                            ->label('Footer background color'),
                        Forms\Components\ColorPicker::make('link_color')
                            ->label('Link color'),
                        Forms\Components\ColorPicker::make('btn_1_color')
                            ->label('Button color 1'),
                        Forms\Components\ColorPicker::make('btn_2_color')
                            ->label('Button color 2'),
                        Forms\Components\ColorPicker::make('btn_3_color')
                            ->label('Button color 3'),
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
