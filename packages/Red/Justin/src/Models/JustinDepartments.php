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

    public static function getOrderShipping($warehouseRef) {
        $cityName = '';
        $warehouseName = '';

        $justin = JustinDepartments::where(['uuid' => $warehouseRef])->first();
        if (!empty($justin)) {
            $cityName = $justin->city_name;
            $warehouseName = $justin->description . ' ' . $justin->address;
        }

        return [
            'cityName' => $cityName,
            'warehouseName' => $warehouseName
        ];

    }

}
