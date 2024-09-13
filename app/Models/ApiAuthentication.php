<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ApiAuthentication extends Model
{
    use HasFactory;

    protected $table = "api_authentications";

    protected $fillable = [
        'app_key',
        'app_secret',
        'created_at',
        'updated_at'
    ];
}
