<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function auth()
    {
        return view('auth.auth'); // ini mengarah ke resources/views/selectEvent.blade.php
    }
}
