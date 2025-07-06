<table id="eventTable" class="table">
    <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Location</th>
            <th>Date</th>
            <th>Ticket Type</th>
            <th>Status</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @forelse($events as $index => $event)
            <tr
                data-id="{{ $event->id }}"
                data-title="{{ $event->title }}"
                data-subtitle="{{ $event->subtitle }}"
                data-date="{{ $event->start_date }}"
                data-location="{{ $event->location }}"
                data-description="{{ $event->description }}"
                data-ticket-names="{{ $event->ticket_names }}"
                data-prices="{{ $event->prices }}"
                data-total-seats="{{ $event->total_seats }}"
                data-picture-event="{{ $event->picture_event }}"
                data-picture-seats="{{ $event->picture_seat }}"
            >
                <td>{{ $index + 1 }}</td>
                <td>{{ $event->title }}</td>
                <td>{{ $event->location }}</td>
                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                <td>{{ $event->type_count }}</td>
                <td>
                    <span class="badge" style="background-color: {{ $event->status == 'Available' ? '#34C759' : '#FFE6E6' }}; color: {{ $event->status == 'Available' ? '#E6FFF2' : '#FE504A' }};">
                        {{ $event->status }}
                    </span>
                </td>
                <td>
                    <div class="menu-container" style="position: relative;">
                        <span class="menu-toggle" onclick="toggleMenu(this)" style="cursor: pointer;">&#8942;</span>
                        <div class="menu-options" style="display: none; position: absolute; right: 0; background: white; border: 1px solid #ccc; box-shadow: 0px 2px 5px rgba(0,0,0,0.2); z-index: 100; border-radius: 5px; overflow: hidden;">
                            <div class="menu-item editData" data-id="{{ $event->id }}"  style="padding: 6px 10px; cursor: pointer;">Edit</div>
                            <div class="menu-item deleteData" data-id="{{ $event->id }}" style="padding: 6px 10px; cursor: pointer; color: red;">Delete</div>
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Tidak ada data events</td></tr>
        @endforelse
    </tbody>
</table>
