<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelSVM extends Model
{
    /** @use HasFactory<\Database\Factories\ModelSVMFactory> */
    use HasFactory;

    protected $guarded = [
        'id',
    ];
}
