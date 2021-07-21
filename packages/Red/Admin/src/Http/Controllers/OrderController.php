<?php

namespace Red\Admin\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Red\Justin\Models\JustinDepartments;
use Red\NP\Models\NpDepartments;
use Webkul\Admin\Http\Controllers\Controller;
use Red\Admin\Repositories\OrderRepository;
use \Webkul\Sales\Repositories\OrderCommentRepository;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $_config;

    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

    /**
     * OrderCommentRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderCommentRepository
     */
    protected $orderCommentRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Red\Admin\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Sales\Repositories\OrderCommentRepository  $orderCommentRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderCommentRepository $orderCommentRepository
    )
    {
        $this->middleware('admin');

        $this->_config = request('_config');

        $this->orderRepository = $orderRepository;

        $this->orderCommentRepository = $orderCommentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function view($id)
    {
        $order = $this->orderRepository->findOrFail($id);
        try {
            $cityName = '';
            $warehouseName = '';
        if (!empty($order->shipping_address)) {
            $warehouseRef = $order->shipping_address->warehouse_ref;
            $cityRef = $order->shipping_address->city_ref;

            if (!empty($warehouseRef) && !empty($cityRef)) {
                if ($order->shipping_method === 'np') {
                    $data = NpDepartments::getOrderShipping($cityRef, $warehouseRef);
                    $cityName = $data['cityName'];
                    $warehouseName = $data['warehouseName'];

                }
                if ($order->shipping_method === 'justin') {
                    $data = JustinDepartments::getOrderShipping($warehouseRef);
                    $cityName = $data['cityName'];
                    $warehouseName = $data['warehouseName'];
                }
            }

        }
        } catch (\Exception $e) {
//            var_dump($e->getMessage());die;
        }


        return view($this->_config['view'], ['order' => $order, 'cityName' => $cityName, 'warehouseName' => $warehouseName]);
    }

    /**
     * Cancel action for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cancel($id)
    {
        $result = $this->orderRepository->cancel($id);

        if ($result) {
            session()->flash('success', trans('admin::app.response.cancel-success', ['name' => 'Order']));
        } else {
            session()->flash('error', trans('admin::app.response.cancel-error', ['name' => 'Order']));
        }

        return redirect()->back();
    }

    /**
     * Add comment to the order
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comment($id)
    {
        $data = array_merge(request()->all(), [
            'order_id' => $id,
        ]);

        $data['customer_notified'] = isset($data['customer_notified']) ? 1 : 0;

        Event::dispatch('sales.order.comment.create.before', $data);

        $comment = $this->orderCommentRepository->create($data);

        Event::dispatch('sales.order.comment.create.after', $comment);

        session()->flash('success', trans('admin::app.sales.orders.comment-added-success'));

        return redirect()->back();
    }
}