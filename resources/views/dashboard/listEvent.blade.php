@extends('layouts.app')

@section('content')
<style>
    .modal-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 1040;
        width: 100vw;
        height: 100vh;
        background-color: rgba(0,0,0,0.5);
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        @include('dashboard.sidebar')

        {{-- List Event --}}
        <div class="col-md-10 p-4" id="listEvent">
            <h4>List Event</h4>
            <div class="d-flex align-items-center mb-3" style="gap: 60px;">
                <input type="text" id="searchBox" class="form-control" placeholder="Search by ID, product, or others..." style="height: 40px;">
                <button class="btn btn-primary" id="openModalBtn" style="background-color: #a78bfa; border: none; height: 40px;">
                    Add
                </button>
            </div>

            <div id="addEventModal" class="modal d-none" tabindex="-1" style="display: block;" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add Event</h5>
                            <button type="button" class="btn-close close-modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Please provide correct and complete information in the fields below.</p>
                            <form id="eventForm">
                                <div class="mb-3">
                                    <label class="form-label">Title *</label>
                                    <input id="inputTitle" type="text" class="form-control" placeholder="Input Event Title">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Short Description (Max. 30) *</label>
                                    <input id="inputShortDes" type="text" class="form-control" placeholder="Input Short Description">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date *</label>
                                    <input id="idStartDate" type="date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Location *</label>
                                    <input id="idLocation" type="text" class="form-control" placeholder="Input Event Location">
                                </div>

                                <div id="ticketTypesContainer">
                                    <div class="row mb-3 ticket-group">
                                        <div class="col-md-4">
                                            <label class="form-label">Title Type *</label>
                                            <input type="text" class="form-control ticket_name" placeholder="Input Title Type">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Price *</label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control price" value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Total Seat *</label>
                                            <input type="number" class="form-control total_seat" value="0">
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btnRemoveTicket">X</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center mb-3">
                                    <button type="button" id="addTicket" class="btn btn-sm w-100" style="border: 1px solid #a78bfa; color: #a78bfa;">+ Add Different Ticket Type</button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description *</label>
                                    <textarea id="idDescription" class="form-control" rows="4" placeholder="Input Description"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Event *</label>
                                    <input id="idPictureEvent" type="file" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Upload Seat Mapping Image</label>
                                    <input id="idPictureSeat" type="file" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="submitBtn" type="button" class="btn btn-primary w-100 close-modal" style="background-color: #a78bfa;">Add Event</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="backdrop" class="modal-backdrop fade show d-none"></div>

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
                <tbody id="tboduyEvent">
                    @if(isset($events))
                        @foreach($events as $index => $event)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $event->title }}</td>
                                <td>{{ $event->location }}</td>
                                <td>{{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}</td>
                                <td>{{ $event->type }}</td>
                                <td>
                                    <span class="{{ $event->status == 'Available' ? 'badge bg-success' : 'badge bg-danger' }}">
                                        {{ $event->status }}
                                    </span>
                                </td>
                                <td class="position-relative">
                                    <div class="menu-container" style="position: relative;">
                                        <span class="menu-toggle" onclick="toggleMenu(this)" style="cursor: pointer;">&#8942;</span>
                                        <div class="menu-options" style="display: none; position: absolute; right: 0; background: white; border: 1px solid #ccc; box-shadow: 0px 2px 5px rgba(0,0,0,0.2); z-index: 100; border-radius: 5px; overflow: hidden;">
                                            <div class="menu-item editData" style="padding: 6px 10px; cursor: pointer;">Edit</div>
                                            <div class="menu-item deleteData" data-id="{{ $event->id }}" style="padding: 6px 10px; cursor: pointer; color: red;">Delete</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr><td colspan="7">Tidak ada data events</td></tr>
                    @endif
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        $('#eventTable').DataTable({
            paging: true,
            pageLength: 10,
            searching: false,
            lengthChange: false,
            info: false
        });

        $('#openModalBtn').click(function () {
            $('#addEventModal').removeClass('d-none').addClass('show');
            $('#backdrop').removeClass('d-none');
        });

        $('.close-modal').click(function () {
            $('#addEventModal').removeClass('show').addClass('d-none');
            $('#backdrop').addClass('d-none');
        });

        $("#addTicket").on("click", function () {
            $("#ticketTypesContainer").append(`
                <div class="row mb-3 ticket-group">
                    <div class="col-md-4">
                        <label class="form-label">Title Type *</label>
                        <input type="text" class="form-control ticket_name" placeholder="Input Title Type">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control price" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Seat *</label>
                        <input type="number" class="form-control total_seat" value="0">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btnRemoveTicket">X</button>
                    </div>
                </div>
            `);
        });

        $(document).on("click", ".btnRemoveTicket", function () {
            $(this).closest(".ticket-group").remove();
        });

        $("#submitBtn").on("click", function (e) {
            e.preventDefault();

            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('title', $("#inputTitle").val());
            formData.append('subtitle', $("#inputShortDes").val());
            formData.append('start_date', $("#idStartDate").val());
            formData.append('location', $("#idLocation").val());
            formData.append('description', $("#idDescription").val());
            formData.append('picture_event', $('#idPictureEvent')[0].files[0]);
            formData.append('picture_seat', $('#idPictureSeat')[0].files[0]);

            $(".ticket_name").each(function (i, el) {
                formData.append(`ticket_name[${i}]`, $(el).val());
            });
            $(".price").each(function (i, el) {
                formData.append(`price[${i}]`, $(el).val());
            });
            $(".total_seat").each(function (i, el) {
                formData.append(`total_seat[${i}]`, $(el).val());
            });

            $.ajax({
                type: "POST",
                url: "{{ route('addEvent') }}",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added!!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500  // popup akan hilang otomatis dalam 1.5 detik
                    });
                    if (response.status === 200) {
                        $("#eventForm")[0].reset();
                        $(".ticket-group").not(":first").remove();
                        $("#addEventModal").addClass("d-none").removeClass("show");
                        $("#backdrop").addClass("d-none");
                        location.reload();
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message || "Terjadi kesalahan."
                    });
                }
            });
        });

        $('#eventTable').on('click', '.menu-toggle', function (e) {
            e.stopPropagation(); // cegah bubble
            $('.menu-options').not($(this).next()).hide(); // tutup menu lain
            $(this).next('.menu-options').toggle();
        });

        // Tutup menu jika klik di luar
        $(document).on('click', function () {
            $('.menu-options').hide();
        });

        // Event delete klik
        $('#eventTable').on('click', '.deleteData', function () {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Tindakan ini tidak bisa dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // window.location.href = '/event/delete/' + id;
                    location.reload();
                }
            });
        });
    });
</script>
@endsection
