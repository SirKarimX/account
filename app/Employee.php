<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'salary',
        'target',
        'commision',
'updated_at',
'created_at',

    ];
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
 public function userPayment($userId)
    {
        $payments  = Payment:: where('user_id', $userId)->orderBy('date', 'desc')->get();
        

        return $payments;
    }
}
