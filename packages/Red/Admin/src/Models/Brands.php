<?php

namespace Red\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $fillable = [
        'red_id',
        'greyd',
        'name',
        'country_brand',
        'image',
        'logo',
        'text',
        'text_uk',
        'track'
    ];
}
