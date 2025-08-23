<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $guarded = [];
    // protected $fillable = [
    //     'user_id',
    //     'type',
    //     'amount',
    //     'status',
    //     'is_sent',
    //     'trans_type',
    //     'payment_status',
    // ];

    /**
     * Each transaction belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
