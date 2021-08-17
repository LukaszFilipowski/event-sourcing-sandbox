<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $first_name
 * @property string $last_name
 */
class Client extends Model
{
    protected $fillable = ['first_name', 'last_name'];
}
