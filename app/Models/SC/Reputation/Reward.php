<?php

namespace App\Models\SC\Reputation;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $table = 'sc_reputation_rewards';

    protected $fillable = [
        'uuid',
        'class_name',
        'editor_name',
        'reputation_amount',
    ];
}
