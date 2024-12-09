<?php

namespace App\Filament\Admin\Resources\SupplierResource\RelationManagers;

use App\Tools\BasePhotoRelationManager;

class PhotosRelationManager extends BasePhotoRelationManager
{
    protected function getPhotoDirectory(): string
    {
        return 'suppliers_photos';
    }
}
