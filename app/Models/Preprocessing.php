<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preprocessing extends Model
{
    /** @use HasFactory<\Database\Factories\PreprocessingFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function Tweet()
    {
        return $this->belongsTo(Tweets::class, 'tweet_id', 'id');
    }
}
