<?php

namespace App\Models;

class Batches extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'progress',
        'filename',
        'status'
    ];
 
}
