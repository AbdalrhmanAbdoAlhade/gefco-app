<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreInfo extends Model
{
    protected $fillable = ['name', 'logo', 'phone', 'email', 'address', 'social_links'];

    protected $casts = [
        'social_links' => 'array',
    ];
}
