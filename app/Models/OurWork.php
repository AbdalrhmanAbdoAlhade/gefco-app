<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OurWork extends Model
{
    use HasFactory;

    protected $table = 'our_works';

    protected $fillable = [
        'name',
        'image',
        'cover_image',
        'description',
    ];
    protected $casts = [
        'image' => 'array',
    ];
}
