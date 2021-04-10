<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $table = 'warehouses';
    public $incrementing = true;

    protected $fillable = [
        'name', 'created_by', 'description',
    ];


    protected $hidden = [

    ];
    
}
