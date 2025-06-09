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

            $bindings = [];
            $searchSql = '';

            if ($search) {
                $searchSql = "WHERE events.id::text LIKE ? OR events.title ILIKE ? OR events.location ILIKE ?";
                $bindings = ["%{$search}%", "%{$search}%", "%{$search}%"];
            }

            $sql = "
                SELECT
                    events.id,
                    events.title,
                    events.location,
                    events.start_date,
                    events.subtitle,
                    events.description,
                    events.picture_event,
                    events.picture_seat,
                    string_agg(ticket_types.ticket_name, ',') AS ticket_names,
                    string_agg(ticket_types.price::text, ',') AS prices,
                    string_agg(ticket_types.total_seat::text, ',') AS total_seats,
                    COUNT(ticket_types.id) AS type_count,
                    CASE
                        WHEN COUNT(ticket_types.id) = 0 THEN 'Sold'
                        ELSE 'Available'
                    END AS status
                FROM events
                LEFT JOIN ticket_types ON events.id = ticket_types.event_id
                $searchSql
                GROUP BY
                    events.id,
                    events.title,
                    events.location,
                    events.start_date,
                    events.subtitle,
                    events.description,
                    events.picture_event,
                    events.picture_seat
                ORDER BY events.created_at DESC
            ";

            $events = DB::select($sql, $bindings);

            // dd($events); // Kalau mau debug

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

    public function editEvent(Request $request, $id)
    {
        $request->validate([
            'title' => 'string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'start_date' => 'date',
            'location' => 'string|max:255',
            'description' => 'nullable|string',
            'picture_event' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'picture_seat' => 'nullable|file|image|mimes:jpg,jpeg,png|max:2048',
            'edit_ticket_name' => 'array',
            'edit_price' => 'array',
            'edit_total_seat' => 'array',
        ]);
        DB::beginTransaction();

        try {

            $event = DB::table('events')->where('id', $id)->first();

            if (!$event) {
                DB::rollBack();
                return response()->json([
                    'status' => 404,
                    'message' => 'Event tidak ditemukan.',
                ], 404);
            }

            $dataUpdate = [
                'title' => $request->input('title'),
                'subtitle' => $request->input('subtitle'),
                'start_date' => $request->input('start_date'),
                'location' => $request->input('location'),
                'description' => $request->input('description'),
            ];
            // dd($dataUpdate);

            if ($request->hasFile('picture_event')) {
                $fileEvent = $request->file('picture_event');
                $filenameEvent = time() . '_event.' . $fileEvent->getClientOriginalExtension();
                $fileEvent->move(public_path('uploads/events'), $filenameEvent);
                $dataUpdate['picture_event'] = $filenameEvent;
            }

            if ($request->hasFile('picture_seat')) {
                $fileSeat = $request->file('picture_seat');
                $filenameSeat = time() . '_seat.' . $fileSeat->getClientOriginalExtension();
                $fileSeat->move(public_path('uploads/seats'), $filenameSeat);
                $dataUpdate['picture_seat'] = $filenameSeat;
            }

            DB::table('events')->where('id', $id)->update($dataUpdate);
            DB::table('ticket_types')->where('event_id', $id)->delete();

            $ticketNames = $request->input('edit_ticket_name');
            $prices = $request->input('edit_price');
            $totalSeats = $request->input('edit_total_seat');

            for ($i = 0; $i < count($ticketNames); $i++) {
                if (empty($ticketNames[$i])) {
                    continue;
                }

                DB::table('ticket_types')->insert([
                    'event_id' => $id,
                    'ticket_name' => $ticketNames[$i],
                    'price' => $prices[$i] ?? 0,
                    'total_seat' => $totalSeats[$i] ?? 0,
                ]);
            }


            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Event dan tiket berhasil diperbarui.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Gagal memperbarui event: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $event = DB::table('events')->where('id', $id)->first();

            if (!$event) {
                return response()->json(['success' => false, 'message' => 'Event tidak ditemukan.']);
            }
            // // Hapus file gambar kalau ada
            // if ($event->picture_event && Storage::exists($event->picture_event)) {
            //     Storage::delete($event->picture_event);
            // }

            // if ($event->picture_seat && Storage::exists($event->picture_seat)) {
            //     Storage::delete($event->picture_seat);
            // }
            // Hapus tiket
            DB::table('ticket_types')->where('event_id', $event->id)->delete();

            // Hapus event
            DB::table('events')->where('id', $id)->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Event dan tiket terkait berhasil dihapus.']);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus event: ' . $e->getMessage()
            ]);
        }
    }




}
