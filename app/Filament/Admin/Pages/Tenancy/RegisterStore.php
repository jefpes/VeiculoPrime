<?php

namespace App\Filament\Admin\Pages\Tenancy;

use App\Models\Store;
use Filament\Forms\Components\TextInput;
use Filament\Forms\{Form, Set};
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Str;

class RegisterStore extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register store';
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

    protected function handleRegistration(array $data): Store
    {
        $store = Store::query()->create($data);

        $store->users()->attach(auth_user());

        return $store;
    }
}
