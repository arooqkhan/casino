<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusUser extends Model
{
    use HasFactory;

    protected $table = 'bonus_users';

    protected $fillable = [
        'bonus_id',
        'user_id',
        'time',
    ];

    // Relations
    public function bonus()
    {
        return $this->belongsTo(Bonus::class, 'bonus_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
