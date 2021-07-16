<?php

namespace Red\NP\Models;

use Illuminate\Database\Eloquent\Model;
use Red\NP\Http\NovaPoshta;

class NpDepartments extends Model
{
    /**
     * @param $cityRef
     * @param $warehouseRef
     * @return array
     */
    public static function getOrderShipping($cityRef, $warehouseRef) {
        $np = new NovaPoshta();
        $cityName = $np->getCitiesName($cityRef);
        $warehouseName = $np->getWarehousesName($cityRef, $warehouseRef);

        return [
            'cityName' => $cityName,
            'warehouseName' => $warehouseName
        ];

    }

}
