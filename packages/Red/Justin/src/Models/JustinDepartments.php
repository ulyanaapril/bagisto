<?php

namespace Red\Justin\Models;

use Illuminate\Database\Eloquent\Model;

class JustinDepartments extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'justin_departments';

}
