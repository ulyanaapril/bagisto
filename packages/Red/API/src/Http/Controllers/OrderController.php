<?php


namespace Red\API\Http\Controllers;


class OrderController extends Controller
{
    protected $_config;

    public function __construct()
    {
        $this->_config = request('_config');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return 222;
    }
}