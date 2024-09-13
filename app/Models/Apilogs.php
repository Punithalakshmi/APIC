<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apilogs extends Model
{
    use HasFactory;


    protected $table = "apilogs";

    protected $fillable = [
        'action',
        'action_type',
        'dealer_id',
        'api_response'
    ];
}
