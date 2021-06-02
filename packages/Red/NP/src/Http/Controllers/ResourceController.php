<?php

namespace Red\NP\Http\Controllers;

use Illuminate\Http\Request;
use Red\NP\Http\NovaPoshta;

class ResourceController extends Controller
{
    /**
     * Contains current guard
     *
     * @var array
     */
    protected $guard;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request) {
        if (!empty($q = $request->get('q'))) {
            $np = new NovaPoshta('uk');
            $cities = $np->getCities(0, $q);
            $cities = array_map(function($city) {
                return array(
                    'id' => $city['Ref'],
                    'text' => $city['Description']
                );
            }, $cities['data']);

            return response()->json($cities);
        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouses(Request $request) {
        if (!empty($search = $request->get('q'))) {
            $np = new NovaPoshta('uk');
            $cities = $np->getWarehouses($search);
            $cities = array_map(function($city) {
                return array(
                    'id' => $city['Ref'],
                    'text' => $city['Description']
                );
            }, $cities['data']);

            return response()->json($cities);
        }
    }

}
