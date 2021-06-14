<?php

namespace Red\NP\Http\Controllers;

use Illuminate\Http\Request;
use Red\NP\Http\NovaPoshta;
use Webkul\Sales\Models\OrderAddress;
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
     * ResourceController constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        OrderRepository $orderRepository
    )
    {
        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->orderRepository = $orderRepository;

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


    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createTtn ($orderId) {
        try {
            if (empty(env('NP_KEY'))) {
                return response()->json([
                    'message' => trans('admin::app.sales.orders.empty-api-key'),
                    'status' => 500
                ]);
            }

            $order = $this->orderRepository->findOrFail($orderId);

            $customerAddress = OrderAddress::where([
                'order_id' => $order->id,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
            ])->first();

            if (empty($customerAddress) || empty($customerAddress->city_ref) || empty($customerAddress->warehouse_ref) || empty($customerAddress->first_name) || empty($customerAddress->last_name)) {
                return response()->json([
                    'message' => trans('admin::app.sales.orders.delivery-address-incomplete'),
                    'status' => 500
                ]);
            }

            $request = request();
            $data = $request->all();

            $np = new NovaPoshta('uk');
            $sender = $np->cloneLoyaltyCounterpartySender('8d5a980d-391c-11dd-90d9-001a92567626');

            if (empty($sender) || !empty($sender['errorCodes'])) {
                return response()->json([
                    'message' => trans('admin::app.sales.orders.error-getting-sender'),
                    'status' => 500
                ]);
            }

            $contactSender = $np->getCounterpartyContactPersons($sender['data'][0]['Ref']);

            $seatsamount = !empty($request->get('seatsamount')) ? $request->get('seatsamount') : 1;
            $weight = !empty($request->get('weight')) ? $request->get('weight') : 0.1;
            $cost = $request->get('cost');
            $cargotype = $request->get('cargotype');

            if ($cargotype > 30){
                $cargotype = 'Cargo';
            } else {
                $cargotype = 'Parcel';
            }

            if (empty($cost)) {
                $cost = 100;
            } else {
                $cost = ceil($cost);
            }

            $sender = array(
                'CitySender' => '8d5a980d-391c-11dd-90d9-001a92567626',//kyiv
                'Sender' => $sender['data'][0]['Ref'],
                'SenderAddress' => '16d300ea-8501-11e4-acce-0050568002cf',//відділення
                'ContactSender' => $contactSender['data'][0]['Ref'],
                'SendersPhone' => $contactSender['data'][0]['Phones'],
            );
            $recipient = array(
                'CounterpartyType' => 'PrivatePerson',
                'FirstName' => $customerAddress->first_name,
                'LastName' => $customerAddress->last_name,
                'RecipientsPhone' => '0638876274',
                'Phone' => '0638876274',
                'City' => $customerAddress->city_ref,
                'CityRecipient' => $customerAddress->city_ref,
                'RecipientAddress' => $customerAddress->warehouse_ref,
                'Warehouse' => $customerAddress->warehouse_ref,

            );
            $params = array(
                'Cost' => $cost,
                'Description' => 'Одяг. Номер заказа: ' . $orderId,
                'Weight' => $weight,
                'DateTime' => date('d.m.Y'),
                'ServiceType' => 'WarehouseWarehouse',
                'PaymentMethod' => 'Cash',
                'PayerType' => 'Recipient',
                'SeatsAmount' => $seatsamount,
                'CargoType' => $cargotype,
                'VolumeGeneral' => '0.1',
            );
            if (!empty($data['type']) && $data['type'] == 16) {
                $params['BackwardDeliveryData'] = [array(
                    'PayerType' => 'Recipient',
                    'CargoType' => 'Money',
                    'RedeliveryString' => $data['cost']
                )];
            }

            $res = $np->newInternetDocument($sender, $recipient, $params);
            if (!empty($res)) {
                $res['message'] = trans('admin::app.sales.orders.successful-created');
                $res['status'] = 200;
                return response()->json($res);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => trans('admin::app.sales.orders.server-error'),
                'logMessage' => $e->getMessage(),
                'status' => 500
            ]);
        }


    }

}
