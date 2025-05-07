<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweets extends Model
{
    /** @use HasFactory<\Database\Factories\TweetsFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function Preprocessing()
    {
        return $this->hasOne(Preprocessing::class, 'tweet_id');
    }
    public function Sentiment()
    {
        return $this->hasOne(Sentiment::class, 'tweet_id');
    }
}
