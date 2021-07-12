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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $point = new StoresDepartments();

        return view('delivery-point::create', compact('point'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        Validator::make($data, [
            'address' => 'required',
            'info' => 'required',
            'active' => 'required',
        ])->validateWithBag('post');


        $point = StoresDepartments::create($data);

        session()->flash('success', trans('admin::app.response.create-success', ['name' => trans('delivery-point::app.delivery-point')]));

        return redirect()->route('admin.deliverypoint.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $point = StoresDepartments::findOrFail($id);

        return view('delivery-point::edit', compact('point'));
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

        session()->flash('success', trans('admin::app.response.update-success', ['name' => trans('delivery-point::app.delivery-point')]));

        return redirect()->route('admin.deliverypoint.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $point = StoresDepartments::findOrFail($id);

        try {

            $point->delete();

            session()->flash('success', trans('admin::app.response.delete-success', ['name' => trans('delivery-point::app.delivery-point')]));

            return response()->json(['message' => true], 200);
        } catch (\Exception $e) {
            session()->flash('error', trans('admin::app.response.delete-failed', ['name' => trans('delivery-point::app.delivery-point')]));
        }

        return response()->json(['message' => false], 400);
    }

    /**
     * Remove the specified resources from database.
     *
     * @return \Illuminate\Http\Response
     */
    public function massDestroy()
    {
        $suppressFlash = true;
        $pointIds = explode(',', request()->input('indexes'));

        foreach ($pointIds as $pointId) {

            $point = StoresDepartments::findOrFail($pointId);

            if (isset($point)) {
                try {
                    $suppressFlash = true;
                    $point->delete();

                } catch (\Exception $e) {
                    session()->flash('error', trans('admin::app.response.delete-failed', ['name' => trans('delivery-point::app.delivery-point')]));
                }
            }
        }

        if (count($pointIds) != 1 || $suppressFlash == true) {
            session()->flash('success', trans('admin::app.datagrid.mass-ops.delete-success', ['resource' => trans('delivery-point::app.delivery-point')]));
        }

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
                'id' => $department['id'],
                'text' => $department['address']
            );
        }, $departments);

        return response()->json($departments);
    }

}
