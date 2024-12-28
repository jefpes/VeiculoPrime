<?php

namespace App\Filament\Admin\Pages\Tenancy;

use Filament\Forms\Components\TextInput;
use Filament\Forms\{Form, Set};
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\Str;

class EditStoreProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Store profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('slug')
                    ->label('Subdomain')
                    ->live(debounce: 1000, onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state, '_')))
                    ->unique()
                    ->visibleOn(['create'])
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
