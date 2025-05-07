<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lexicon extends Model
{
    /** @use HasFactory<\Database\Factories\LexiconFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];
}
