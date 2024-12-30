<?php

namespace App\Filament\Admin\Resources\StoreResource\Pages;

use App\Filament\Admin\Resources\StoreResource;
use App\Models\Store;
use Filament\Resources\Pages\CreateRecord;

class CreateStore extends CreateRecord
{
    protected static string $resource = StoreResource::class;

    protected function afterCreate(): void
    {
        Store::find($this->record->id)->users()->attach(auth_user()->id); //@phpstan-ignore-line
    }
}
