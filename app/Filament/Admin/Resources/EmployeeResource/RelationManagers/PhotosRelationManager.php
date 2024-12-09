<?php

namespace App\Filament\Admin\Resources\EmployeeResource\RelationManagers;

use App\Tools\BasePhotoRelationManager;

class PhotosRelationManager extends BasePhotoRelationManager
{
    protected function getPhotoDirectory(): string
    {
        return 'employee_photos';
    }
}
