@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        {{-- Sidebar --}}
        <div class="col-md-2">
            <div class="d-flex flex-column align-items-start p-3 bg-white rounded-4 shadow-sm h-100" style="min-height: 100vh;">
                <img src="{{ asset('images/logo.png') }}" alt="Tixboom Logo" class="mb-5" style="height: 40px;">

                <ul class="nav nav-pills flex-column mb-auto w-100">
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link active fw-semibold" style="background-color: #B487F8;">
                            <i class="bi bi-grid me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-4">
                        <a href="#" class="nav-link text-dark fw-semibold">
                            <i class="bi bi-card-list me-2"></i> List Event
                        </a>
                    </li>
                </ul>

                <div class="mt-auto w-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('images/profile.png') }}" alt="Admin" class="rounded-circle me-2" width="40" height="40">
                        <div class="fw-semibold">Admin 1</div>
                    </div>
                    <a href="#" class="btn w-100 text-white fw-bold" style="background-color: #B487F8;">Log Out</a>
                </div>
            </div>
        </div>

        {{-- Main Dashboard --}}
        <div class="col-md-10">
            <h4 class="fw-bold mb-4">Dashboard</h4>

            {{-- Cards --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <div class="text-muted mb-2">Total List Event</div>
                        <h4 class="fw-bold">24</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <div class="text-muted mb-2">Total Income</div>
                        <h4 class="fw-bold">Rp 20.000.000</h4>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 bg-white rounded-4 shadow-sm">
                        <div class="text-muted mb-2">Total Orders</div>
                        <h4 class="fw-bold">3,500</h4>
                    </div>
                </div>
            </div>

            {{-- Sold Ticket List --}}
            <div class="bg-white p-4 rounded-4 shadow-sm">
                <h5 class="fw-bold mb-4">Sold Ticket List</h5>
                <table id="ticketTable" class="table table-hover">
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
                    <tbody>
                        @php $tickets = [
                            ['event' => 'Tulus : Manusia Vol I', 'name' => 'Chieko Chute', 'type' => 'VIP Front Seat', 'price' => 'Rp 800.000'],
                            ['event' => 'Tulus : Manusia Vol I', 'name' => 'Annabel Rohan', 'type' => 'VIP Front Seat', 'price' => 'Rp 800.000'],
                            ['event' => 'Tulus : Manusia Vol I', 'name' => 'Pedro Huard', 'type' => 'Festival Seat', 'price' => 'Rp 400.000'],
                            ['event' => 'ST12 : Mengenjreng Dunia', 'name' => 'Jamel Eusebio', 'type' => 'Festival Seat', 'price' => 'Rp 400.000'],
                            ['event' => 'ST12 : Mengenjreng Dunia', 'name' => 'Augustina Midgett', 'type' => 'Festival Seat', 'price' => 'Rp 400.000'],
                        ]; @endphp
                        @foreach ($tickets as $index => $ticket)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $ticket['event'] }}<br><span class="text-muted small">#ID238976</span></td>
                            <td>{{ $ticket['name'] }}</td>
                            <td>Apr 24, 2022, 07.20</td>
                            <td>{{ $ticket['type'] }}</td>
                            <td>{{ $ticket['price'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function () {
        $('#ticketTable').DataTable({
            paging: true,
            pageLength: 5,
            lengthChange: false,
            info: false
        });
    });
</script>
@endsection
