<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
class ClientPhoto extends BasePhoto
{
    protected function getPhotoDirectory(): string
    {
        return 'client_photos';
    }

    protected function getPhotoNamePrefix(): string
    {
        return Str::slug($this->client->name);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
