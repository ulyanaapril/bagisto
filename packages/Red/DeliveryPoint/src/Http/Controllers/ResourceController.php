<?php

namespace Red\DeliveryPoint\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('delivery-point::index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $category = StoresDepartments::findOrFail($id);

        return view('delivery-point::edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $data = request()->all();

        Validator::make($data, [
            'address' => 'required',
            'info' => 'required',
            'active' => 'required',
        ])->validateWithBag('post');

        DB::table('stores_departments')
            ->where(['id' => $id])
            ->update([
                'address' => $data['address'],
                'info' => $data['info'],
                'active' => $data['active'],
                'type' => 1,
            ]);

        session()->flash('success', trans('admin::app.response.update-success', ['name' => 'Category']));

        return redirect()->route('admin.deliverypoint.index');
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
