<?php

namespace Red\DeliveryPoint\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Sales\Models\OrderAddress;
use Webkul\Shop\Http\Controllers\Controller;
use Webkul\Checkout\Facades\Cart;
use Webkul\Shipping\Facades\Shipping;
use Webkul\Payment\Facades\Payment;
use Webkul\Checkout\Http\Requests\CustomerAddressForm;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Customer\Repositories\CustomerRepository;

class OnepageController extends Controller
{
    /**
     * OrderRepository object
     *
     * @var \Webkul\Sales\Repositories\OrderRepository
     */
    protected $orderRepository;

     /**
     * customerRepository instance object
     *
     * @var \Webkul\Customer\Repositories\CustomerRepository
     */
    protected $customerRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Attribute\Repositories\OrderRepository  $orderRepository
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @return void
     */
    public function __construct(
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository
    )
    {
        $this->orderRepository = $orderRepository;

        $this->customerRepository = $customerRepository;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        Event::dispatch('checkout.load.index');

        if (! auth()->guard('customer')->check()
            && ! core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout')) {
            return redirect()->route('customer.session.index');
        }

        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        if (! auth()->guard('customer')->check() && $cart->hasDownloadableItems()) {
            return redirect()->route('customer.session.index');
        }

        if (! auth()->guard('customer')->check() && ! $cart->hasGuestCheckoutItems()) {
            return redirect()->route('customer.session.index');
        }

        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (! $cart->checkMinimumOrder()) {
            session()->flash('warning', trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));

            return redirect()->back();
        }

        Cart::collectTotals();

        return view($this->_config['view'], compact('cart'));
    }

    /**
     * Return order short summary
     *
     * @return \Illuminate\Http\Response
    */
    public function summary()
    {
        $cart = Cart::getCart();

        return response()->json([
            'html' => view('shop::checkout.total.summary', compact('cart'))->render(),
        ]);
    }

    /**
     * Saves customer address.
     *
     * @param  \Webkul\Checkout\Http\Requests\CustomerAddressForm  $request
     * @return \Illuminate\Http\Response
    */
    public function saveAddress(CustomerAddressForm $request)
    {
        $data = request()->all();

        if (! auth()->guard('customer')->check() && ! Cart::getCart()->hasGuestCheckoutItems()) {
            return response()->json(['redirect_url' => route('customer.session.index')], 403);
        }

        $data['billing']['company_name'] = !empty($data['billing']['company_name']) ? $data['billing']['company_name'] : 'Company';

        $data['billing']['address1'] = implode(PHP_EOL, array_filter($data['billing']['address1']));
        $data['shipping']['address1'] = implode(PHP_EOL, array_filter($data['shipping']['address1']));

        if (Cart::hasError() || ! Cart::saveCustomerAddress($data)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        } else {
            $cart = Cart::getCart();

            Cart::collectTotals();

            if ($cart->haveStockableItems()) {
                if (! $rates = Shipping::collectRates()) {
                    return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
                } else {
                    return response()->json($rates);
                }
            } else {
                return response()->json(Payment::getSupportedPaymentMethods());
            }
        }
    }

    /**
     * Saves shipping method.
     *
     * @return \Illuminate\Http\Response
    */
    public function saveShipping()
    {
        $shippingMethod = request()->get('shipping_method');

        $warehouse = request()->get('warehouse_ref');

        if (empty($warehouse)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        if (Cart::hasError() || !$shippingMethod || !Cart::saveShippingMethod($shippingMethod)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        return response()->json(Payment::getSupportedPaymentMethods());
    }

    /**
     * Saves payment method.
     *
     * @return \Illuminate\Http\Response
     * @throws \Throwable
     */
    public function savePayment()
    {
        $payment = request()->get('payment');

        if (Cart::hasError() || ! $payment || ! Cart::savePaymentMethod($payment)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $cart = Cart::getCart();

        return response()->json([
            'jump_to_section' => 'review',
            'html'            => view('shop::checkout.onepage.review', compact('cart'))->render(),
        ]);
    }


    /**
     * @return \Illuminate\Http\JsonResponse
     * Validate on save order. Will be redirect to refresh cart page on error
     * @throws \Exception
     */
    public function saveOrder()
    {
        $warehouse = request()->get('warehouse_ref');

        if (empty($warehouse)) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        if (Cart::hasError()) {
            return response()->json(['redirect_url' => route('shop.checkout.cart.index')], 403);
        }

        Cart::collectTotals();

        $this->validateOrder();

        $cart = Cart::getCart();

        if ($redirectUrl = Payment::getRedirectUrl($cart)) {
            return response()->json([
                'success'      => true,
                'redirect_url' => $redirectUrl,
            ]);
        }

        $customerAddress = CartAddress::where([
            'cart_id' => $cart->id,
            'customer_id' => $cart->customer_id,
            'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING
        ])->first();
        if (!empty($customerAddress)) {
            $customerAddress->fillable(array_merge($customerAddress->getFillable(),["warehouse_ref"]));
            $customerAddress->fill(['warehouse_ref' => $warehouse])->save();
        }

        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        if (!empty($cart)) {
            $customerAddress = OrderAddress::where([
                'order_id' => $order->id,
                'customer_id' => $cart->customer_id,
                'address_type' => OrderAddress::ADDRESS_TYPE_SHIPPING
            ])->first();
            if (!empty($customerAddress)) {
                $customerAddress->fillable(array_merge($customerAddress->getFillable(),['warehouse_ref']));
                $customerAddress->fill(['warehouse_ref' => $warehouse])->save();
            }
        }

        Cart::deActivateCart();

        session()->flash('order', $order);

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Order success page
     *
     * @return \Illuminate\Http\Response
    */
    public function success()
    {
        if (! $order = session('order')) {
            return redirect()->route('shop.checkout.cart.index');
        }

        return view($this->_config['view'], compact('order'));
    }

    /**
     * Validate order before creation
     *
     * @return void|\Exception
     */
    public function validateOrder()
    {
        $cart = Cart::getCart();

        $minimumOrderAmount = core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        if (! $cart->checkMinimumOrder()) {
            throw new \Exception(trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]));
        }

        if ($cart->haveStockableItems() && ! $cart->shipping_address) {
            throw new \Exception(trans('Please check shipping address.'));
        }

        if (! $cart->billing_address) {
            throw new \Exception(trans('Please check billing address.'));
        }

        if ($cart->haveStockableItems() && ! $cart->selected_shipping_rate) {
            throw new \Exception(trans('Please specify shipping method.'));
        }

        if (! $cart->payment) {
            throw new \Exception(trans('Please specify payment method.'));
        }
    }

    /**
     * Check Customer is exist or not
     *
     * @return \Illuminate\Http\Response
     */
    public function checkExistCustomer()
    {
       $customer = $this->customerRepository->findOneWhere([
            'email' => request()->email,
       ]);

       if (! is_null($customer)) {
           return 'true';
       }

       return 'false';
    }

    /**
     * Login for checkout
     *
     * @return \Illuminate\Http\Response
     */
    public function loginForCheckout()
    {
        $this->validate(request(), [
            'email' => 'required|email'
        ]);

        if (! auth()->guard('customer')->attempt(request(['email', 'password']))) {
            return response()->json(['error' => trans('shop::app.customer.login-form.invalid-creds')]);
        }

        Cart::mergeCart();

        return response()->json(['success' => 'Login successfully']);
    }

    /**
     * To apply couponable rule requested
     *
     * @return \Illuminate\Http\Response
     */
    public function applyCoupon()
    {
        $this->validate(request(), [
            'code' => 'string|required',
        ]);

        $code = request()->input('code');

        $result = $this->coupon->apply($code);

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('shop::app.checkout.total.coupon-applied'),
                'result'  => $result,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('shop::app.checkout.total.cannot-apply-coupon'),
                'result'  => null,
            ], 422);
        }

        return $result;
    }

    /**
     * Initiates the removal of couponable cart rule
     *
     * @return array
     */
    public function removeCoupon()
    {
        $result = $this->coupon->remove();

        if ($result) {
            Cart::collectTotals();

            return response()->json([
                'success' => true,
                'message' => trans('admin::app.promotion.status.coupon-removed'),
                'data'    => [
                    'grand_total' => core()->currency(Cart::getCart()->grand_total),
                ],
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => trans('admin::app.promotion.status.coupon-remove-failed'),
                'data'    => null,
            ], 422);
        }
    }

    /**
     * Check for minimum order.
     *
     * @return \Illuminate\Http\Response
     */
    public function checkMinimumOrder()
    {
        $minimumOrderAmount = (float) core()->getConfigData('sales.orderSettings.minimum-order.minimum_order_amount') ?? 0;

        $status = Cart::checkMinimumOrder();

        return response()->json([
            'status' => ! $status ? false : true,
            'message' => ! $status ? trans('shop::app.checkout.cart.minimum-order-message', ['amount' => core()->currency($minimumOrderAmount)]) : 'Success',
        ]);
    }
}