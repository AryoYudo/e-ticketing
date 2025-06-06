<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DetailController extends Controller
{
    public function detail($id)
    {
        // Ambil event berdasarkan ID
        $event = DB::table('events')->where('id', $id)->first();
        if (!$event) {
            abort(404);
        }
        $ticketTypes = DB::table('ticket_types')
                        ->where('event_id', $id)
                        ->orderBy('price', 'asc')
                        ->get();

        // Ambil harga tiket paling murah (kalau mau ditampilkan)
        $minPrice = $ticketTypes->min('price');
        return view('detailEvents.detail', [
            'event' => $event,
            'ticketTypes' => $ticketTypes,
            'minPrice' => $minPrice
        ]); // ini mengarah ke resources/views/selectEvent.blade.php
    }
}
