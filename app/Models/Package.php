<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $guarded = [];


    public function users()
{
    return $this->belongsToMany(User::class, 'package_user')
                ->withPivot('time')
                ->withTimestamps();
}


}
