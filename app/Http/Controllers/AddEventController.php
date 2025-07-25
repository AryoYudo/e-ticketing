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
                        WHEN SUM(ticket_types.total_seat) <= 0 THEN 'Sold'
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

            // Jika request via AJAX (buat search live)
            if ($request->ajax()) {
                return response()->view('dashboard.partials.eventTable', compact('events'));
            }

            // Untuk page full
            return view('dashboard.listEvent', compact('events', 'search'));

        } catch (\Exception $e) {
            Log::error('Gagal memuat daftar event: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memuat data event.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function addEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
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
            // Upload file gambar
            $pictureEventPath = null;
            $pictureSeatPath = null;

            if ($request->hasFile('picture_event')) {
                $pictureEventPath = $request->file('picture_event')->store('event_images', 'public');
            }

            if ($request->hasFile('picture_seat')) {
                $pictureSeatPath = $request->file('picture_seat')->store('seat_images', 'public');
            }

            // Tambahkan event
            $eventId = DB::table('events')->insertGetId([
                'title' => $request->input('title'),
                'subtitle' => $request->input('subtitle'),
                'start_date' => $request->input('start_date'),
                'location' => $request->input('location'),
                'description' => $request->input('description'),
                'picture_event' => $pictureEventPath,
                'picture_seat' => $pictureSeatPath,
                'created_at' => now(),
            ]);

            // Insert tiket
            $ticketNames = $request->input('ticket_name');
            $prices = $request->input('price');
            $totalSeats = $request->input('total_seat');

            $ticketCount = min(count($ticketNames), count($prices), count($totalSeats));

            for ($i = 0; $i < $ticketCount; $i++) {
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
            Log::error('Add Event Failed: ' . $e->getMessage());
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

            // Ambil semua ticket lama
            $oldTickets = DB::table('ticket_types')->where('event_id', $id)->get();

            foreach ($oldTickets as $ticket) {
                $hasOrder = DB::table('orders')->where('ticket_type_id', $ticket->id)->exists();

                if (!$hasOrder) {
                    DB::table('ticket_types')->where('id', $ticket->id)->delete();
                }
            }

            $ticketNames = $request->input('edit_ticket_name');
            $prices = $request->input('edit_price');
            $totalSeats = $request->input('edit_total_seat');

            for ($i = 0; $i < count($ticketNames); $i++) {
                if (empty($ticketNames[$i])) {
                    continue;
                }

                $existing = DB::table('ticket_types')
                    ->where('event_id', $id)
                    ->where('ticket_name', $ticketNames[$i])
                    ->first();

                if ($existing) {
                    $hasOrder = DB::table('orders')->where('ticket_type_id', $existing->id)->exists();

                    if (!$hasOrder) {
                        DB::table('ticket_types')->where('id', $existing->id)->update([
                            'price' => $prices[$i] ?? 0,
                            'total_seat' => $totalSeats[$i] ?? 0,
                        ]);
                    }
                    // Jika sudah dipakai, tidak diubah (biar aman)
                } else {
                    DB::table('ticket_types')->insert([
                        'event_id' => $id,
                        'ticket_name' => $ticketNames[$i],
                        'price' => $prices[$i] ?? 0,
                        'total_seat' => $totalSeats[$i] ?? 0,
                    ]);
                }
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

            // Hapus orders dulu agar tidak melanggar FK ke ticket_types
            DB::table('orders')->where('event_id', $id)->delete();

            // Lalu hapus ticket_types
            DB::table('ticket_types')->where('event_id', $event->id)->delete();

            // Terakhir hapus event
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
