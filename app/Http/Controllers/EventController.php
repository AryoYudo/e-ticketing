<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class EventController extends Controller
{
    public function events(Request $request)
    {
        try {
            $month = $request->query('month'); // misal ?month=7
            $query = DB::table('events')
                ->leftJoin('ticket_types', 'events.id', '=', 'ticket_types.event_id')
                ->select('events.*', DB::raw('MIN(ticket_types.price) as min_price'));

            if ($month) {
                $query->whereMonth('events.start_date', $month);
            }

            $events = $query
                ->groupBy('events.id', 'events.title', 'events.location', 'events.start_date')
                ->get();


            return view('selectEvent.selectEvent', [
                'events' => $events,
                'selectedMonth' => $month
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching events: ' . $e->getMessage());
            return view('events.index')->with('events', []);
        }
    }
}
