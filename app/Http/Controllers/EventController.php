<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class EventController extends Controller
{
    public function events()
    {
        return view('selectEvent.selectEvent'); // ini mengarah ke resources/views/selectEvent.blade.php
    }
}
