<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Dealer extends Model
{
    use HasFactory;

    protected $table = "dealers";

    protected $fillable = [
        'dealer_id',
        'name',
        'appuid',
        'email',
        'status',
        'time_of_url_generation',
        'current_url',
        'onboarding_date',
        'apic_user_type',
        'token',
        'is_token_generated',
        'created_at',
        'updated_at'
    ];
}
