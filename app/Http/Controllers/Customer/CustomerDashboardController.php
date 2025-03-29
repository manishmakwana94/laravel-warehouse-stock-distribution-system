<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
     /**
     * Display the customer dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can pass data to the view as needed.
        return view('customer.dashboard');
    }
}
