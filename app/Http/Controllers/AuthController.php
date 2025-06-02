<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{
    public function auth()
    {
        return view('auth.auth');
    }

    public function login(Request $request)
    {
        // Step 1: Validasi
        $request->validate([
            'emailAdmin' => 'required|email',
            'password' => 'required',
        ]);

        // Step 2: Ambil input dari ajax
        $email = $request->input('emailAdmin');
        $password = $request->input('password');

        // Step 3: Ambil user dari DB
        $admin = DB::table('admin')->where('email', $email)->first();
        if (!$admin) {
            return response()->json([
                'status' => 401,
                'message' => 'Email not found'
            ], 401);
        }

        // Step 4: Cek password
        $hashedPassword = $admin->passwrd ?? $admin->password;
        // Cek isi hashed password
        if (!$hashedPassword) {
            return response()->json([
                'status' => 500,
                'message' => 'Password field is missing in DB'
            ]);
        }

        if (!Hash::check($password, $hashedPassword)) {
            return response()->json([
                'status' => 401,
                'message' => 'Incorrect password'
            ], 401);
        }

        // Step 5: Simpan session
        $request->session()->put('loggedInUser', [
            'email' => $admin->email,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Successfully logged in'
        ]);
    }



}
