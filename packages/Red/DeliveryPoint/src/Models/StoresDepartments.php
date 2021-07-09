<?php

namespace Red\DeliveryPoint\Models;

use Illuminate\Database\Eloquent\Model;

class StoresDepartments extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'stores_departments';

}
