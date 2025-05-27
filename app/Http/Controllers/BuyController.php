<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BuyController extends Controller
{
    public function buy()
    {
        return view('formBuy.formBuy'); // ini mengarah ke resources/views/selectEvent.blade.php
    }
}
