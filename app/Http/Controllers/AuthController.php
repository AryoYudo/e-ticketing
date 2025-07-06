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
        try {
            // Step 1: Validasi
            $request->validate([
                'emailAdmin' => 'required|email',
                'password' => 'required|min:6|max:15',
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
                'id' => $admin->id,
                'email' => $admin->email,
                'name' => $admin->name ?? null,
            ]);


            return response()->json([
                'status' => 200,
                'message' => 'Successfully logged in'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Kalau validasi gagal
            return response()->json([
                'status' => 422,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Kalau error lain, misal DB error dll
            return response()->json([
                'status' => 500,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }




}
