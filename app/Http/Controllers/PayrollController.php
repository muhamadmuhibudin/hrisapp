<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $payrolls = Payroll::all();
        return view ('payrolls.index', compact ('payrolls'));
    }


}
