<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class EmployeePhoto
 *
 * @property \App\Models\Supplier $supplier
 *
 * @method \App\Models\Supplier supplier()
 *
 * @property int $id
 * @property int $supplier_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */

class SupplierPhoto extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($photo) {
            Storage::disk('public')->delete($photo->path);
        });

        static::saving(function ($photo) {
            if ($photo->isDirty('path')) {
                $oldPhoto = $photo->getOriginal('path');

                // Gerando o novo nome do arquivo
                $newFileName = sprintf(
                    '%s_%s.%s',
                    Str::slug($photo->supplier->name),
                    (string) Str::uuid(),
                    pathinfo($photo->path, PATHINFO_EXTENSION)
                );

                // Definindo o novo caminho para o arquivo
                $newFilePath = 'supplier_photos/' . $newFileName;

                // Movendo o arquivo para o novo caminho
                Storage::disk('public')->move($photo->path, $newFilePath);

                // Atualizando o caminho no modelo
                $photo->path = $newFilePath;

                // Deletando a foto antiga se estiver atualizando
                if ($oldPhoto && $oldPhoto !== $photo->path) {
                    Storage::disk('public')->delete($oldPhoto);
                }
            }
        });
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
