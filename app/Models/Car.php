<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $brand_name
 * @property string $model_name
 * @property string $vin
 */
class Car extends Model
{
    protected $fillable = ['brand_name', 'model_name', 'vin'];
}
