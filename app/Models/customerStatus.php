<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customerStatus extends Model {

    use HasFactory;

    protected $fillable = [
       'status', 'position','user_id'
    ];

}
