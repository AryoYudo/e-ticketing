<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str; // Untuk generate order_id unik
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use App\Mail\TicketMail;
use Illuminate\Support\Facades\Mail;
use PDF;



class BuyController extends Controller
{
    public function buy($id)
    {
        $ticketTypes = DB::table('ticket_types')
                        ->where('id', $id)
                        ->orderBy('price', 'asc')
                        ->first();

        $eventName = DB::table('events')->where('id', $ticketTypes->event_id)->value('title');

        return view('formBuy.formBuy', [
            'ticketTypes' => $ticketTypes,
            'eventName' => $eventName,
        ]);
    }

    public function orderRequest(Request $request, $ticket_id)
    {
        $request->validate([
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|string|email|max:255',
            'nik' => 'required|string|max:255',
            'birth_date' => 'required|date',
        ]);

        try {
            Config::$serverKey = 'SB-Mid-server-k_u3IuY-P6AKInHLPhZsYsHv';
            Config::$isProduction = false;

            $ticket = DB::table('ticket_types')->where('id', $ticket_id)->where('total_seat', '>', 0)->first();
            if (!$ticket) {
                return response()->json(['status' => 400, 'message' => 'Tiket tidak tersedia']);
            }

            // Buat order_id unik
            $order_id = 'ORDER-' . strtoupper(Str::random(10));
            // Kurangi seat
            DB::table('ticket_types')->where('id', $ticket->id)->decrement('total_seat');

            // Simpan order
            DB::table('orders')->insert([
                'midtrans_order_id' => $order_id,
                'event_id' => $ticket->event_id,
                'ticket_type_id' => $ticket->id,
                'buyer_name' => $request->buyer_name,
                'buyer_email' => $request->buyer_email,
                'nik' => $request->nik,
                'birth_date' => $request->birth_date,
                'total_payment' => $ticket->price,
                'order_date' => now(),
            ]);

            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $ticket->price,
                ],
                'customer_details' => [
                    'first_name' => $request->buyer_name,
                    'email' => $request->buyer_email,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            $details = [
                'order_id' => $order_id,
                'buyer_name' => $request->buyer_name,
                'buyer_email' => $request->buyer_email,
                'nik' => $request->nik,
                'birth_date' => $request->birth_date,
                'total_payment' => $ticket->price,
            ];

            Mail::to($request->buyer_email)->send(new TicketMail($details));

            return response()->json([
                'status' => 200,
                'token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
