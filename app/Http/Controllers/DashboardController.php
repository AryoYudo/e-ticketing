<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class DashboardController extends Controller
{
    // public function dashboard()
    // {
    //     return view('dashboard.dashboard'); // ini mengarah ke resources/views/selectEvent.blade.php
    // }

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

    public function addEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'picture_event' => 'nullable|string',
            'picture_seat' => 'nullable|string',
            'ticket_name' => 'required|array',
            'price' => 'required|array',
            'total_seat' => 'required|array',
        ]);

        try {
            // Tambahkan event baru
            $eventId = DB::table('events')->insertGetId([
                'title' => $request->input('title'),
                'subtitle' => $request->input('subtitle'),
                'start_date' => $request->input('start_date'),
                'location' => $request->input('location'),
                'description' => $request->input('description'),
                'picture_event' => $request->input('picture_event'),
                'picture_seat' => $request->input('picture_seat'),
                'created_at' => now(),
            ]);

            // Loop insert semua tipe tiket
            $ticketNames = $request->input('ticket_name');
            $prices = $request->input('price');
            $totalSeats = $request->input('total_seat');

            for ($i = 0; $i < count($ticketNames); $i++) {
                DB::table('ticket_types')->insert([
                    'event_id' => $eventId,
                    'ticket_name' => $ticketNames[$i],
                    'price' => $prices[$i],
                    'total_seat' => $totalSeats[$i],
                    'created_at' => now(),
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Event dan tiket berhasil ditambahkan.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Gagal menambahkan event: ' . $e->getMessage(),
            ]);
        }
    }

    public function showEventList()
    {
        try {
            // Ambil data events dan jumlah tipe tiket
            $events = DB::table('events')
                ->leftJoin('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                ->select(
                    'events.id',
                    'events.title',
                    'events.location',
                    'events.start_date',
                    DB::raw('COUNT(ticket_types.id) as type'),
                    DB::raw("CASE
                                WHEN COUNT(ticket_types.id) = 0 THEN 'Sold'
                                ELSE 'Available'
                            END as status")
                )
                ->groupBy('events.id', 'events.title', 'events.location', 'events.start_date')
                ->orderByDesc('events.created_at')
                ->get();

            return view('dashboard.dashboard', compact('events')); // Ganti 'yourbladefilename' sesuai Blade kamu

        } catch (\Exception $e) {
            Log::error('Gagal memuat daftar event: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data event.');
        }
    }
}
