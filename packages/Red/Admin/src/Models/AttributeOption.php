<?php

namespace Red\Admin\Models;

use Red\Admin\Contracts\AttributeOption as AttributeOptionContract;
use Webkul\Attribute\Models\AttributeOption as AttributeOptionBase;

class AttributeOption extends AttributeOptionBase implements AttributeOptionContract
{
    public $timestamps = false;

    public $translatedAttributes = ['label'];

    protected $fillable = [
        'admin_name',
        'swatch_value',
        'sort_order',
        'attribute_id',
        'id_1c',
        'red_id',
        'category_id'
    ];

}