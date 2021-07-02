<?php

namespace Red\Ukrposhta\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Red\Ukrposhta\Http\UkrPostAPI;
use Red\Ukrposhta\Models\UkrpostClients;
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
                    'message' => 'Error',
                    'data' => $validator->errors(),
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
                    $data['postcode'], $data['state'],
                    $data['city'], $data['district'],
                    $data['street'], $data['house'],
                    $data['apartment'],
                    $order->shipping_address->email
                );
                if (!empty($client->uuid)) {
                    $cl = new UkrpostClients();
                    $cl->fill([
                        'customer_id' => 1,
                        'uuid' => $client->uuid,
                        'name' => $client->name,
                        'external_id' => $client->externalId,
                        'counterparty_uuid' => $client->counterpartyUuid,
                        'address_id' => $client->addressId,
                        'type' => $client->type
                    ])->save();
                }
            } else {
                $params = [
                    'uuid' => $cl->uuid,
                    'name' => $cl->name,
                    'firstName' => $data['firstName'],
                    'middleName' => '',
                    'lastName' => $data['lastName'],
                    'addressId' => $cl->address_id,
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
                $data['description'] = 'test',
                $data['transferPostPayToBankAccount'],
                $data['paidByRecipient'],
                $data['postPayPaidByRecipient']
            );

            if (!empty($s->barcode)) {
                return response()->json([
                    'message' => 'ok',
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
     * @param $orderId
     */
    public function printTtn($orderId){
        $up = new UkrPostAPI();
        $order = $this->orderRepository->findOrFail($orderId);
        $pdf = $up->getSticker100_100('0503076516270');
        header('Content-type: application/pdf');
        echo '<div style="padding: 10px;    margin: 10px;">' . $pdf . '</div>';

    }

}
