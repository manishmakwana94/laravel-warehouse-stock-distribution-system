<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseDashboardControlle extends Controller
{
    /**
     * Display the warehouse dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You can pass data to the view as needed.
        return view('warehouse.dashboard');
    }
}
