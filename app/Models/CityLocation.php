<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityLocation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'latitude', 'longitude','city_location',
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\CityLocation', 'user_id', 'id');
    }



}
