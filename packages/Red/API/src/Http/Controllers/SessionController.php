<?php

namespace Red\API\Http\Controllers;

use Illuminate\Support\Facades\Event;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\API\Http\Resources\Customer\Customer as CustomerResource;

class SessionController extends Controller
{
    /**
     * Contains current guard
     *
     * @var string
     */
    protected $guard;

    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * Controller instance
     *
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     */
    public function __construct(CustomerRepository $customerRepository)
    {
        $this->guard = request()->has('token') ? 'admin-api' : 'admin';

        auth()->setDefaultDriver($this->guard);

        $this->middleware('auth:' . $this->guard, ['only' => ['get', 'destroy']]);

        $this->_config = request('_config');

        $this->customerRepository = $customerRepository;
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        request()->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $jwtToken = null;

        if (! $jwtToken = auth()->guard($this->guard)->attempt(request()->only('email', 'password'))) {
            return response()->json([
                'error' => 'Invalid Email or Password',
            ], 401);
        }

        Event::dispatch('customer.after.login', request('email'));

        $customer = auth($this->guard)->user();

        return response()->json([
            'token'   => $jwtToken,
            'message' => 'Logged in successfully.',
            'data'    => new CustomerResource($customer),
        ]);
    }

    /**
     * Get details for current logged in customer
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        $customer = auth($this->guard)->user();

        return response()->json([
            'data' => new CustomerResource($customer),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        auth()->guard($this->guard)->logout();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }
}
