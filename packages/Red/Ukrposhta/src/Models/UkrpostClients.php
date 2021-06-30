<?php

namespace Red\Ukrposhta\Models;

use Illuminate\Database\Eloquent\Model;

class UkrpostClients extends Model
{
    /**
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * @var string
     */
    protected $table = 'ukrpost_clients';

}
