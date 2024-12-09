<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class VehicleDocPhoto
 *
 * @property \App\Models\Vehicle $vehicle
 *
 * @method \App\Models\Vehicle vehicle()
 *
 * @property int $id
 * @property int $vehicle_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class VehicleDocPhoto extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($photo) {
            Storage::disk('public')->delete($photo->path);
        });

        static::updating(function ($photo) {
            $oldPhoto = $photo->getOriginal('path');

            // Gerando o novo nome do arquivo
            $newFileName = sprintf(
                '%s_%s.%s',
                Str::slug($photo->vehicle->plate),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'vehicle_doc_photos/' . $newFileName;

            // Movendo o arquivo para o novo caminho
            Storage::disk('public')->move($photo->path, $newFilePath);

            // Atualizando o caminho no modelo sem acionar outro evento de atualização
            $photo->path = $newFilePath;

            // Deletando a foto antiga
            Storage::disk('public')->delete($oldPhoto);
        });

        static::creating(function ($photo) {
            // Gerando o novo nome do arquivo
            $newFileName = sprintf(
                '%s_%s_%s_%s.%s',
                Str::slug($photo->vehicle->model->name),
                $photo->vehicle->year_one,
                Str::slug($photo->vehicle->color),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'vehicle_photos/' . $newFileName;

            // Movendo o arquivo para o novo caminho
            Storage::disk('public')->move($photo->path, $newFilePath);

            // Atualizando o caminho no modelo sem acionar outro evento de criação
            $photo->path = $newFilePath;
        });
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
