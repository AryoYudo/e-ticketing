@extends('layouts.app')

@section('content')
<style>

</style>
<div class="container-fluid py-4">
    <div class="row">
        @include('dashboard.sidebar')

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
        // Menu toggle
        $('#dashboardLink').on('click', function(e) {
            e.preventDefault();

            $('#dashboardLink').addClass('active').css('background-color', '#B487F8');
            $('#listEventLink').removeClass('active').css('background-color', '');
        });

        $('#listEventLink').on('click', function(e) {
            $('#listEventLink').addClass('active').css('background-color', '#B487F8');
            $('#dashboardLink').removeClass('active').css('background-color', '');
        });

        // AJAX Load Dashboard Data
        $.ajax({
            url: '{{ route("listEvents") }}',
            method: 'GET',
            success: function (response) {
                if (response.status === 200) {
                    const data = response.data;
                    $('#totalEvents').text(data.totalEvents);
                    $('#totalIncome').text('Rp ' + formatRupiah(data.totalIncome));
                    $('#totalOrders').text(data.totalOrders);

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
