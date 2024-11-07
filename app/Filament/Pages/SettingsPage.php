<?php

namespace App\Filament\Pages;

use App\Enums\Font;
use App\Models\Settings;
use Filament\Forms\{Form};
use Filament\Pages\Page;
use Filament\{Forms};
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Auth;

class SettingsPage extends Page
{
    protected static string $view = 'filament.pages.settings';

    protected static ?int $navigationSort = 4;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Auth::user()->settings->toArray()); //@phpstan-ignore-line
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
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make()->schema([
                        Forms\Components\Select::make('font')
                        ->allowHtml()
                        ->options(
                            collect(Font::cases())
                                ->mapWithKeys(static fn ($case) => [
                                    $case->value => "<span style='font-family:{$case->getLabel()}'>{$case->getLabel()}</span>",
                                ]),
                        )
                        ->native(false),
                        Forms\Components\Select::make('navigation_mode')
                            ->label(__('Navigation mode'))
                            ->options([
                                false => __('Sidebar'),
                                true  => __('Topbar'),
                            ])
                            ->native(true)
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
                ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Settings::query()->whereUserId(Auth::id())->update($this->form->getState()); //@phpstan-ignore-line
    }
}
