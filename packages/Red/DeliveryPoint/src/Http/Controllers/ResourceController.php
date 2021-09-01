<?php

namespace Red\DeliveryPoint\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Inventory\Repositories\InventorySourceRepository;
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
     * @var
     */
    protected $inventorySourceRepository;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orderRepository
     * @param InventorySourceRepository $inventorySourceRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        InventorySourceRepository $inventorySourceRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->inventorySourceRepository = $inventorySourceRepository;

        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouses(Request $request) {
        $channelInventorySources = core()->getCurrentChannel()
            ->inventory_sources()
            ->where('status', 1)
            ->where('code', '!=', 'BF0000001')
            ->get()
            ->toArray();

        $channelInventorySources = array_map(function($source) {
            return array(
                'id' => $source['id'],
                'text' => !empty($source['description']) ? $source['description'] : $source['name']
            );
        }, $channelInventorySources);

        return response()->json($channelInventorySources);
    }

}
