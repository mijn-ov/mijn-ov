<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'user_id',
        'day',
        'time',
        'start_address',
        'end_address',
        'duration',
        'travel_type',
    ];
}
