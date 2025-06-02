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

    public function listEvents()
    {
        try {
            // Ambil semua data events
            $events = DB::table('events')->get();

            // Ambil list orders terbaru (10 terakhir)
            $listOrders = DB::table('orders')
                ->join('events', 'orders.event_id', '=', 'events.id')
                ->join('ticket_types', 'orders.ticket_type_id', '=', 'ticket_types.id')
                ->select(
                    'orders.id as order_id',
                    'orders.buyer_name',
                    'orders.total_payment',
                    'orders.transaction_time',
                    'events.title as event_title',
                    'events.start_date',
                    'ticket_types.ticket_name as ticket_type'
                )
                ->orderBy('orders.transaction_time', 'desc')
                ->limit(10)
                ->get();

            // Hitung summary
            $totalEvents = $events->count();
            $totalIncome = DB::table('orders')->sum('total_payment');
            $totalOrders = DB::table('orders')->count();

            // Susun response
            $data = [
                'events' => $events,
                'totalEvents' => $totalEvents,
                'totalIncome' => $totalIncome,
                'totalOrders' => $totalOrders,
                'listOrders' => $listOrders,
            ];

            return response()->json([
                'status' => 200,
                'message' => 'Data berhasil diambil.',
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage(),
            ], 500);
        }
    }


}
