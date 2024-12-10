<?php

namespace App\Filament\Admin\Resources\VehicleResource\RelationManagers;

use App\Tools\BasePhotoRelationManager;

class DocPhotosRelationManager extends BasePhotoRelationManager
{
    protected static string $relationship = 'docPhotos';

    protected static ?string $label = 'Foto de documento';

    protected static ?string $title = 'Fotos de documentos';

    protected function getPhotoDirectory(): string
    {
        return 'vehicle_doc_photos';
    }
}
