<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class AddEventController extends Controller
{
    public function showEventTabel(Request $request)
    {
        try {
            $search = $request->input('search');

            $query = DB::table('events')
                ->leftJoin('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                ->select(
                    'events.id',
                    'events.title',
                    'events.location',
                    'events.start_date',
                    DB::raw('COUNT(ticket_types.id) as type'),
                    DB::raw("CASE WHEN COUNT(ticket_types.id) = 0 THEN 'Sold' ELSE 'Available' END as status")
                )
                ->groupBy('events.id', 'events.title', 'events.location', 'events.start_date')
                ->orderByDesc('events.created_at');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('events.id', 'like', "%{$search}%")
                    ->orWhere('events.title', 'like', "%{$search}%")
                    ->orWhere('events.location', 'like', "%{$search}%");
                });
            }

            $events = $query->get();

            return view('dashboard.listEvent', compact('events', 'search'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat daftar event: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data event.');
        }
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string',
            'picture_event' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'picture_seat' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
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

}
