<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Vehicle extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::saved(function ($vehicle) {
            // Renomear as fotos após o veículo ser salvo
            foreach ($vehicle->photos as $photo) {
                if ($photo->path) {
                    $newFileName = sprintf(
                        '%s_%s_%s_%s.%s',
                        Str::slug($vehicle->model->name),
                        $vehicle->year_one,
                        Str::slug($vehicle->color),
                        (string) Str::uuid(),
                        pathinfo($photo->path, PATHINFO_EXTENSION)
                    );

                    $newFilePath = 'vehicle_photos/' . $newFileName;
                    Storage::disk('public')->move($photo->path, $newFilePath);
                    $photo->update(['path' => $newFilePath]);
                }
            }
        });
    }

    public function getCombinedYearsAttribute(): string
    {
        return "{$this->year_one}/{$this->year_two}"; //@phpstan-ignore-line
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id', 'id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(VehiclePhoto::class);
    }

    public function sale(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(VehicleExpense::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
