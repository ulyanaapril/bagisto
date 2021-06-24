<?php

namespace Red\Ukrposhta\Http\Controllers;

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
        $up = new \UkrPostAPI();
        $request = request();
        $data = $request->all();

        $s = $up->getShipmentInGroups(
            $data['shipmentGroupUuid'],
            $data['recipient_uuid'],
            $data['lastName'],
            $data['firstName'],
            $data['middleName'],
            $data['externalId'],
            $data['weight'],
            $data['length'],
            $data['declaredPrice'],
            $data['postPay'],
            $data['description'],
            $data['transferPostPayToBankAccount'],
            $data['paidByRecipient'],
            $data['postPayPaidByRecipient']
        );

        if ($s->uuid) {
//            $ord->setNakladna($s->barcode);
//            $this->_redir('ukrpost/print/sticker/shipment/uuid/' . $s->uuid);

        }
        return response()->json([
            'message' => 'ok',
            'status' => 200
        ]);
    }

}
