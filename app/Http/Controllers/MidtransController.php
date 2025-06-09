<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Midtrans\Config;
use Midtrans\Notification;
use App\Mail\TicketMail;

class MidtransController extends Controller
{
    public function handleNotification(Request $request)
    {
        Config::$serverKey = 'SB-Mid-server-k_u3IuY-P6AKInHLPhZsYsHv';
        Config::$isProduction = false;

        $notification = new Notification();
        $transaction = $notification->transaction_status;
        $order_id = $notification->order_id;

        if ($transaction === 'settlement' || $transaction === 'capture') {
            $order = DB::table('orders')->where('midtrans_order_id', $order_id)->first();

            if ($order) {
                $details = [
                    'order_id' => $order->midtrans_order_id,
                    'buyer_name' => $order->buyer_name,
                    'buyer_email' => $order->buyer_email,
                    'nik' => $order->nik,
                    'birth_date' => $order->birth_date,
                    'total_payment' => $order->total_payment,
                ];

                Mail::to($order->buyer_email)->send(new TicketMail($details));
            }
        }

        return response()->json(['message' => 'Notification handled']);
    }
}
