<?php

namespace Red\Ukrposhta\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Red\Ukrposhta\Http\UkrPostAPI;
use Red\Ukrposhta\Models\UkrpostClients;
use Webkul\Customer\Repositories\CustomerAddressRepository;
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
     * @var CustomerAddressRepository
     */
    protected $customerAddressRepository;

    /**
     * Create a new controller instance.
     *
     * @param OrderRepository $orderRepository
     * @param CustomerAddressRepository $customerAddressRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        CustomerAddressRepository $customerAddressRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->customerAddressRepository = $customerAddressRepository;

        $this->guard = request()->has('token') ? 'api' : 'customer';

        $this->_config = request('_config');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cities(Request $request)
    {
        return response()->json(
            [
                ['id' => '', 'text' => '']
            ]
        );

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function warehouses(Request $request) {
        return response()->json(
            [
                ['id' => '', 'text' => '']
            ]
        );
    }

    /**
     * @param $orderId
     * @return \Illuminate\Http\JsonResponse
     */
    public function createTtn ($orderId) {
        try {
            $up = new UkrPostAPI();

            $request = request();
            $data = $request->all();

            $validator = Validator::make($data, [
                'lastName' => 'required',
                'firstName' => 'required',
                'phone' => 'required',
                'postcode' => 'required',
                'city' => 'required',
                'state'=> 'required',
                'district' =>  'required',
                'street'=> 'required',
                'house' => 'required',
                'apartment'=> 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ]);
            }

            $order = $this->orderRepository->findOrFail($orderId);
            $cl = UkrpostClients::where(['customer_id' => $order->customer_id])->first();

            if (empty($cl)) {
                $client = $up->getNewClient(
                    $data['lastName'],
                    $data['firstName'],
                    '',
                    $data['phone'],
                    '_' . $order->customer_id,
                    $data['postcode'],
                    $data['state'],
                    $data['city'],
                    $data['district'],
                    $data['street'],
                    $data['house'],
                    $data['apartment'],
                    $order->shipping_address->email
                );
                if (!empty($client->uuid)) {
                    $cl = new UkrpostClients();
                    $cl->fill([
                        'customer_id' => $order->customer_id,
                        'uuid' => $client->uuid,
                        'name' => $client->name,
                        'external_id' => $client->externalId,
                        'counterparty_uuid' => $client->counterpartyUuid,
                        'address_id' => $client->addressId,
                        'type' => $client->type
                    ])->save();
                    $this->updateAddressesTable($order, $data);
                }
            } else {
                if ($order->shipping_address->postcode != $data['postcode'] || $order->shipping_address->city != $data['city']
                    || $order->shipping_address->state != $data['state'] || $order->shipping_address->district != $data['district']
                    || $order->shipping_address->street != $data['street'] || $order->shipping_address->house != $data['house']
                    || $order->shipping_address->apartment != $data['apartment']) {
                    $address = $up->newAddress($data['postcode'], $data['state'], $data['district'], $data['city'], $data['street'], $data['house'], $data['apartment'], $cl->external_id);
                    if (!empty($address->id)) {
                        $res = $up->putAddressClient($cl->uuid, $address->id);
                        if (!empty($res->addressId)) {
                            $cl->fill(['address_id' => $address->id])->save();
                            $this->updateAddressesTable($order, $data);
                        } else {
                            throw new \Exception('Помилка добавлення адресу клієнта');
                        }
                    } else {
                        throw new \Exception('Помилка створення адресу клієнта');
                    }
                }

                $params = [
                    'uuid' => $cl->uuid,
                    'name' => $cl->name,
                    'firstName' => $data['firstName'],
                    'middleName' => '',
                    'lastName' => $data['lastName'],
                    'addressId' => !empty($address->id) ? $address->id : $cl->address_id,
                    'phoneNumber' => $data['phone'],
                    'externalId' => $cl->external_id,
                    'counterpartyUuid' => 'ce4e19d6-6a4f-4f1f-a8c4-5b4a1f9cde9a'
                ];

                $client = $up->getEditClient($cl->uuid, $params);
            }

            if (empty($client->uuid) && !empty($client->message)) {
                throw new \Exception($client->message);
            }

            $s = $up->getNewShipments(
//                $data['shipmentGroupUuid'],
                $data['recipient_uuid'] = $client->uuid,
                $data['lastName'] = $client->firstName,
                $data['firstName'] = $client->lastName,
                $data['middleName'] = '',
                $data['externalId'] = $order->id,
                $data['weight'] = !empty($data['weight']) ? $data['weight'] : 10,
                $data['length'] = !empty($data['length']) ? $data['length'] : 10,
                $data['declaredPrice'],
                $data['postPay'],
                $data['description'],
                $data['transferPostPayToBankAccount'],
                $data['paidByRecipient'],
                $data['postPayPaidByRecipient']
            );

            if (!empty($s->barcode)) {
                return response()->json([
                    'message' => trans('admin::app.sales.orders.ttn-created-successfully'),
                    'data' => json_decode(json_encode($s), true),
                    'status' => 200
                ], 200);
            } else if (!empty($s->message)){
                throw new \Exception($s->message);
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500,
                'logMessage' => $e->getMessage()
            ], 200);
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
                'postcode' => $data['postcode'],
                'state' => $data['state'],
                'city' => $data['city'],
                'district' => $data['district'],
                'street' => $data['street'],
                'house' => $data['house'],
                'apartment' => $data['apartment']
            ]);
    }

    /**
     * @param Request $request
     */
    public function printTtn(Request $request){
        $trackNumber = $request->input('trackNumber');
        $up = new UkrPostAPI();
        $pdf = $up->getSticker100_100($trackNumber);
        header('Content-type: application/pdf');
        echo '<div style="padding: 10px;    margin: 10px;">' . $pdf . '</div>';

    }

}
