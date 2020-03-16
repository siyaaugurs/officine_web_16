<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiamondApis extends Model
{
    protected $fillable = [
        'name',
        'url',
        'input_params',
    ];

    /**
     * Get the input_params.
     *
     * @param  string  $value
     * @return void
     */
    public function getInputParamsAttribute($value)
    {
        return empty($value) ? '' : json_decode($value, true);
    }
}
