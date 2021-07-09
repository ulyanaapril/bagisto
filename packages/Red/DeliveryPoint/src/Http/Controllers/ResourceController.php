<?php

namespace Red\DeliveryPoint\Http\Controllers;

use Illuminate\Http\Request;
use Red\DeliveryPoint\Models\StoresDepartments;
use Webkul\Sales\Repositories\OrderRepository;

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
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouses(Request $request) {
        $departments = StoresDepartments::where(['active' => 1])->get()->toArray();

        $departments = array_map(function($department) {
            return array(
                'id' => $department['uuid'],
                'text' => $department['depart_descr'] . ' ' . $department['address']
            );
        }, $departments);

        return response()->json($departments);
    }

}
