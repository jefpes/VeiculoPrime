<?php

namespace App\Filament\Admin\Resources\ClientResource\RelationManagers;

use App\Tools\BasePhotoRelationManager;

class PhotosRelationManager extends BasePhotoRelationManager
{
    protected function getPhotoDirectory(): string
    {
        return 'client_photos';
    }
}
