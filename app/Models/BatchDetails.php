<?php

namespace App\Models;

class BatchDetails extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'size',
        'style',
        'unique_key',
        'title',
        'color_name',
        'sanmar_mainframe_color',
        'batch_id',
        'piece_price',
        'description'
    ];
 
}
