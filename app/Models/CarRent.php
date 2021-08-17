<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property Car $car
 * @property Client $client
 * @property DateTime $from
 * @property DateTime $to
 */
class CarRent extends Model
{
    protected $fillable = ['from', 'to'];

    protected $casts = [
        'from' => 'datetime',
        'to' => 'datetime'
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
