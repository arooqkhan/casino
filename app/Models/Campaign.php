<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{

    protected $fillable = [
    'name',
    'description',
    'status',
    'start_at',
    'end_at',
    'countdown_end',
    'terms',
];


    public function bonuses()
    {
        return $this->hasMany(Bonus::class);
    }


    protected $casts = [
    'start_at'      => 'datetime',
    'end_at'        => 'datetime',
    'countdown_end' => 'datetime',
];


}
