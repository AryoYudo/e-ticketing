@extends('layouts.app')

@section('content')
<style>

</style>
<div class="container-fluid py-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2">
            <div class="col-md-2 position-fixed bg-white shadow-sm vh-100 d-flex flex-column p-3" style="z-index: 1000;">
                <div class="d-flex justify-content-center mb-4">
                    {{-- Logo --}}
                    <img src="{{ asset('images/logo.png') }}" alt="Tixboom Logo" class="mb-2" style="height: 50px;">
                </div>

                <ul class="nav nav-pills flex-column mb-auto w-100">
                    <li class="nav-item mb-2">
                        <a href="#" id="dashboardLink" class="nav-link text-dark fw-semibold" style="background-color: #B487F8;">
                            <i class="bi bi-grid me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-4" >
                        <a href="#" id="listEventLink" class="nav-link text-dark fw-semibold">
                            <i class="bi bi-card-list me-2"></i> List Event
                        </a>
                    </li>
                </ul>

                <div class="mt-auto w-100 mb-4  ">
                    <div class="d-flex align-items-center justify-content-center mb-1">
                        <i class="bi bi-person-circle fs-3 me-2"></i>
                        <div class="fw-semibold">Admin 1</div>
                    </div>

                    <a href="#" class="btn w-100 text-white fw-bold" style="background-color: #B487F8;">Log Out</a>
                </div>
            </div>
        </div>

        {{-- Main Dashboard --}}
         <div class="col-md-10 offset-md-2 overflow-auto vh-100" id="mainDashboard">
            <h2 class="fw-bol p-4">Dashboard</h2>

            {{-- Cards --}}
            <div class="row mb-4 p-4">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow">
                        <div class="text-muted mb-2">Total List Event</div>
                        <h4 class="fw-bold" id="totalEvents">0</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow">
                        <div class="text-muted mb-2">Total Income</div>
                        <h4 class="fw-bold" id="totalIncome">Rp 0</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow">
                        <div class="text-muted mb-2">Total Orders</div>
                        <h4 class="fw-bold" id="totalOrders">0</h4>
                    </div>
                </div>
            </div>

            {{-- Sold Ticket List --}}
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h5 class="fw-bold mb-4">Sold Ticket List</h5>
                <table id="ticketTable" class="table table-hover shadow-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Orders</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Ticket Type</th>
                            <th>Payment Total</th>
                        </tr>
                    </thead>
                    <tbody id="ticketBody">
                    </tbody>
                </table>
            </div>
        </div>

        {{-- List Event --}}
        <div class="col-md-10 d-none p-4 " id="listEvent">
            <h4>List Event</h4>
           <div class="d-flex align-items-center mb-3" style="gap: 40px;">
                <input type="text" id="searchBox" class="form-control" placeholder="Search by ID, product, or others..." style="height: 40px;">
                <button class="btn btn-primary" style="background-color: #a78bfa; border: none; height: 40px;">
                    + Add Event
                </button>
            </div>

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
                    @php
                        $events = [
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Available'],
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Sold'],
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Available'],
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Sold'],
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Available'],
                            ['title' => 'SmokeShack Burger', 'location' => 'Chieko Chute', 'date' => 'Apr 24, 2022', 'type' => 3, 'status' => 'Available'],
                        ];
                    @endphp
                    @foreach($events as $index => $event)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $event['title'] }}</td>
                        <td>{{ $event['location'] }}</td>
                        <td>{{ $event['date'] }}</td>
                        <td>{{ $event['type'] }}</td>
                        <td>
                            <span class="{{ $event['status'] == 'Available' ? 'badge bg-success' : 'badge bg-danger' }}">
                                {{ $event['status'] }}
                            </span>
                        </td>
                        <td>⋮</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#eventTable').DataTable({
            paging: true,
            pageLength: 6,
            searching: false,
            lengthChange: false,
            info: false
        });

        // Handle menu toggle
        $('#dashboardLink').on('click', function(e) {
            e.preventDefault();
            $('#mainDashboard').removeClass('d-none');
            $('#listEvent').addClass('d-none');

            $('#dashboardLink').addClass('active').css('background-color', '#B487F8');
            $('#listEventLink').removeClass('active').css('background-color', '');
        });

        $('#listEventLink').on('click', function(e) {
            e.preventDefault();
            console.log('List Event Link Clicked');
            $('#mainDashboard').addClass('d-none');
            $('#listEvent').removeClass('d-none');

            $('#listEventLink').addClass('active').css('background-color', '#B487F8');
            $('#dashboardLink').removeClass('active').css('background-color', '');
        });
    });

    $(document).ready(function () {
        $.ajax({
            url: '{{ route("listEvents") }}',
            method: 'GET',
            success: function (response) {
                if (response.status === 200) {
                    const data = response.data;
                    // Update cards
                    $('#totalEvents').text(data.totalEvents);
                    $('#totalIncome').text('Rp ' + formatRupiah(data.totalIncome));
                    $('#totalOrders').text(data.totalOrders);

                    // Update table
                    let tbody = '';
                    data.listOrders.forEach((order, index) => {
                        tbody += `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${order.event_title}<br><span class="text-muted small">#ID${order.order_id}</span></td>
                                <td>${order.buyer_name}</td>
                                <td>${formatDate(order.start_date)}</td>
                                <td>${order.ticket_type}</td>
                                <td>Rp ${formatRupiah(order.total_payment)}</td>
                            </tr>
                        `;
                    });

                    $('#ticketBody').html(tbody);
                    $('#ticketTable').DataTable({
                        paging: true,
                        searching: false,
                        pageLength: 5,
                        lengthChange: false,
                        info: false
                    });
                } else {
                    console.error('Gagal mengambil data:', response.message);
                }
            },
            error: function (xhr) {
                console.error('Terjadi kesalahan:', xhr.responseText);
            }
        });

        // Formatter helpers
        function formatRupiah(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
        }
    });

</script>
@endsection
