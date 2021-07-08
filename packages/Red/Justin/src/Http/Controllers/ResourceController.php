<?php

namespace Red\Justin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Red\Justin\Http\JustinClass;
use Red\Justin\Models\JustinDepartments;
use Webkul\Sales\Models\Order;
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
     * Sending request to make order (create waybill)
     *
     * @param array $data Order data:
     *                    number => Int, internal order id
     *                    date => String, date of order create format('Ymd' | 'Y-m-d')
     *                    sender_city_id => String, external city id, use $this->getCities()
     *                    sender_company => String, sender company name
     *                    sender_contact => String, sender full name
     *                    sender_phone => String, sender phone
     *                    sender_pick_up_address => String, address fo cargo pickup
     *                    pick_up_is_required => Boolean, is need cargo pickup on address
     *                    sender_branch => String, sender external department number, use $this->getDepartments()
     *                    receiver => String, recipient full name
     *                    receiver_contact => String, recipient full name (if another person receive cargo)
     *                    receiver_phone => String, recipient phone number
     *                    count_cargo_places => Int, count places
     *                    branch => String, recipient extend department number, use $this->getDepartments()
     *                    weight => Float, cargo weight
     *                    volume => Float, cargo volume
     *                    declared_cost => Int, declared cargo price
     *                    delivery_amount => Int, money redelivery (param delivery_payment_is_required)
     *                    redelivery_amount => Int, redelivery tax
     *                    order_amount => Int, all sum
     *                    redelivery_payment_is_required => Boolean, is need redelivery tax
     *                    redelivery_payment_payer => Int, tax payer (0 - sender | 1 - recipient)
     *                    delivery_payment_is_required => Boolean, is delivery payment required
     *                    delivery_payment_payer => Int, delivery payer (0 - sender | 1 - recipient)
     *                    order_payment_is_required => Boolean, is order payment required
     *
     * @return mixed
     * @param $orderId
     */
    public function createTtn($orderId) {
        try {
            $order = $this->orderRepository->findOrFail($orderId);
            $data = request()->all();

            $validator = Validator::make($data, [
                'receiver' => 'required',
                'receiver_phone' => 'required',
                'count_cargo_places' => 'required',
                'weight' => 'required|numeric|min:0|not_in:0',
                'declared_cost' => 'required|numeric|min:0|not_in:0',
                'delivery_payment_payer' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ]);
            }

            $branch = JustinDepartments::where(['uuid' => $data['warehouse_ref']])->first()->branch;
            $arr = [
                'number' => $orderId,
                'date' => date('Y-m-d'),
                'sender_city_id' => '32b69b95-9018-11e8-80c1-525400fb7782',
                'sender_company' => 'RED',
                'sender_contact' => 'Куковицкий Сергей',
                'sender_phone' => '+380674069080',
                'sender_pick_up_address' => '',
                'pick_up_is_required' => false,
                'sender_branch' => '7204200108',
                'receiver' => $data['receiver'],
                'receiver_contact' => '',
                'receiver_phone' => $data['receiver_phone'],
                'count_cargo_places' => $data['count_cargo_places'],
                'branch' => $branch,
                'weight' => !empty($data['weight']) ? $data['weight'] : 1,
                'volume' => 1,
                'declared_cost' => $data['declared_cost'],
                'delivery_amount'=> 0,
                'redelivery_amount' => 0,
                'order_amount' => $data['declared_cost'],
                'redelivery_payment_is_required' => false,
                'redelivery_payment_payer' => 0,
                'delivery_payment_is_required' => true,
                'delivery_payment_payer' => $data['delivery_payment_payer'],
                'order_payment_is_required' => true
            ];

            $justin = new JustinClass();

            $res = $justin->createOrder($arr);

            if (!empty($res['result']) && $res['result'] == "success") {
                $arr['number_ttn'] = $res['data']['ttn'];
                $arr['number_pms'] = $res['data']['number'];
                $this->updateAddressesTable($order, $data);
                return response()->json([
                    'message' => 'ТТН створено успішно',
                    'data' => $res,
                    'status' => 200
                ]);
            } else {
                if (!empty($res['result']) && $res['result'] == "error" && !empty($res['errors'])) {
                    return response()->json([
                        'message' => $res['errors'][0]['error'],
                        'data' => $res['errors'],
                        'status' => 500
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Помилка сервера',
                        'data' => $res,
                        'status' => 500
                    ]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
               'message' => $e->getMessage(),
               'status' => 500,
               'logMessage' => $e->getMessage()
            ]);
        }

    }

    /**
     * @param $order Order
     * @param $data
     */
    private function updateAddressesTable($order, $data) {
        DB::table('addresses')
            ->where(['order_id' => $order->id, 'customer_id' => $order->customer_id, 'address_type' => 'order_shipping'])
            ->update([
                'city_ref' => $data['city_ref'],
                'warehouse_ref' => $data['warehouse_ref']
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {

        if ($request->get('refresh-departments')) {
            $justin = new JustinClass();

            $res = $justin->getDepartments([], 1000)['data'];

            $list = [];
            $i = 1;
            if (!empty($res) && is_array($res)) {
                foreach ($res as $value) {
                    $list[$i]['branch'] = $value['fields']['branch'];
                    $list[$i]['number'] = $value['fields']['departNumber'];
                    $list[$i]['uuid'] = $value['fields']['Depart']['uuid'];
                    $list[$i]['depart_descr'] = $value['fields']['Depart']['descr'];
                    $list[$i]['description'] = $value['fields']['descr'];
                    $list[$i]['region_uuid'] = $value['fields']['region']['uuid'];
                    $list[$i]['region_name'] = $value['fields']['region']['descr'];
                    $list[$i]['city_uuid'] = $value['fields']['city']['uuid'];
                    $list[$i]['city_name'] = $value['fields']['city']['descr'];
                    $list[$i]['street_uuid'] = $value['fields']['street']['uuid'];
                    $list[$i]['street_name'] = $value['fields']['street']['descr'];
                    $list[$i]['street_number'] = $value['fields']['houseNumber'];
                    $list[$i]['weight_limit'] = $value['fields']['weight_limit'];
                    $list[$i]['address'] = $value['fields']['address'];
                    $list[$i]['lat'] = $value['fields']['lat'];
                    $list[$i]['lng'] = $value['fields']['lng'];
                    $list[$i]['type_value'] = $value['fields']['TypeDepart']['value'];
                    $list[$i]['type_descr'] = $value['fields']['TypeDepart']['enum'];

                    $i++;
                }
                if (!empty($list)) {
                    JustinDepartments::truncate();
                    foreach ($list as $value) {
                        $department = new JustinDepartments();
                        $department->fill($value);
                        $department->save();
                    }
                }
            }

        }


        if (!empty($q = $request->get('term'))) {
            $cities = JustinDepartments::where('city_name', 'like', '%' . $q . '%')->get()->unique('city_uuid')->toArray();
            $cities = array_map(function ($city) {
                return array(
                    'id' => $city['city_uuid'],
                    'text' => $city['city_name']
                );
            }, $cities);

            array_unshift($cities, '');

            return response()->json($cities);

        }

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function city(Request $request) {
        if (!empty($search = $request->get('q'))) {
            $city = JustinDepartments::where(['city_uuid' => $search])->take(1)->get()->toArray();

            $city = array_map(function ($city) {
                return array(
                    'id' => $city['city_uuid'],
                    'text' => $city['city_name']
                );
            }, $city);

            return response()->json($city);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouse(Request $request) {
        if (!empty($search = $request->get('q'))) {
            $warehouse = JustinDepartments::where(['city_uuid' => $search])->get()->toArray();

            $warehouse = array_map(function ($warehouse) {
                return array(
                    'id' => $warehouse['uuid'],
                    'text' => $warehouse['description'] . ', ' . $warehouse['address'],
                );
            }, $warehouse);

            return response()->json($warehouse);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouses(Request $request) {
        if (!empty($search = $request->get('q'))) {
            $departments = JustinDepartments::where(['city_uuid' => $search])->get()->toArray();

            $departments = array_map(function($department) {
                return array(
                    'id' => $department['uuid'],
                    'text' => $department['depart_descr'] . ' ' . $department['address']
                );
            }, $departments);

            return response()->json($departments);
        }
    }

}
