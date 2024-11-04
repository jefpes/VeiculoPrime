<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Class ClientPhoto
 *
 * @property \App\Models\Client $client
 *
 * @method \App\Models\Client client()
 *
 * @property int $id
 * @property int $client_id
 * @property string $path
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class ClientPhoto extends Model
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
                Str::slug($photo->client->name),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'client_photos/' . $newFileName;

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
                '%s_%s.%s',
                Str::slug($photo->client->name),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'client_photos/' . $newFileName;

            // Movendo o arquivo para o novo caminho
            Storage::disk('public')->move($photo->path, $newFilePath);

            // Atualizando o caminho no modelo sem acionar outro evento de criação
            $photo->path = $newFilePath;
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
