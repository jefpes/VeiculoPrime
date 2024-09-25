<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EmployeePhoto extends Model
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
                Str::slug($photo->employee->name),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'employee_photos/' . $newFileName;

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
                Str::slug($photo->employee->name),
                (string) Str::uuid(),
                pathinfo($photo->path, PATHINFO_EXTENSION)
            );

            // Definindo o novo caminho para o arquivo
            $newFilePath = 'employee_photos/' . $newFileName;

            // Movendo o arquivo para o novo caminho
            Storage::disk('public')->move($photo->path, $newFilePath);

            // Atualizando o caminho no modelo sem acionar outro evento de criação
            $photo->path = $newFilePath;
        });
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
