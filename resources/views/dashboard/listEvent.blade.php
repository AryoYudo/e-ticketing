@extends('layouts.app')

@section('content')
<head>
    <!-- tag meta lain -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- css, js, dll -->
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
        .swal2-popup .swal-cancel-custom {
            border: 1px solid #ccc;
            background-color: transparent !important;
            color: #333 !important;
            box-shadow: none !important;
            transition: 0.3s;
        }

        .swal2-popup .swal-cancel-custom:hover {
            background-color: #f5f5f5 !important;
            border-color: #bbb;
        }


        .dataTables_wrapper .dataTables_paginate .page-item .page-link {
            color: #a78bfa !important;
            background-color: transparent !important;
            border: none !important;
            font-weight: 500;
            margin: 0 2px;
            border-radius: 8px;
        }

        /* Hover */
        .dataTables_wrapper .dataTables_paginate .page-item .page-link:hover {
            background-color: rgba(167, 139, 250, 0.1) !important;
            color: #7c3aed !important;
        }

        /* Active page */
        .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
            color: #a78bfa !important;
            background-color: transparent !important;
            text-decoration: underline;
            font-weight: bold;
            box-shadow: none !important;
        }

        /* Disable focus outline */
        .dataTables_wrapper .dataTables_paginate .page-link:focus {
            box-shadow: none !important;
        }

        /* Previous / Next arrows */
        .dataTables_wrapper .dataTables_paginate .page-item.previous .page-link,
        .dataTables_wrapper .dataTables_paginate .page-item.next .page-link {
            color: #a78bfa !important;
        }


    </style>
</head>
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
                                    <label class="form-label">Title <span style="color:red">*</span></label>
                                    <input id="inputTitle" type="text" class="form-control" required placeholder="Input Event Title">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Short Description (Max. 30) <span style="color:red">*</span></label>
                                    <input id="inputShortDes" type="text" class="form-control" required placeholder="Input Short Description">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Date <span style="color:red">*</span></label>
                                    <input id="idStartDate" type="date" class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Location <span style="color:red">*</span></label>
                                    <input id="idLocation" type="text" class="form-control" required placeholder="Input Event Location">
                                </div>

                                <div id="ticketTypesContainer">
                                    <div class="row mb-3 ticket-group">
                                        <div class="col-md-4">
                                            <label class="form-label">Title Type <span style="color:red">*</span></label>
                                            <input type="text" class="form-control ticket_name" required placeholder="Input Title Type">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Price <span style="color:red">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" class="form-control price" required value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Total Seat <span style="color:red">*</span></label>
                                            <input type="number" class="form-control total_seat" required value="0">
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
                                    <label class="form-label">Description <span style="color:red">*</span></label>
                                    <textarea id="idDescription" class="form-control" rows="4" required placeholder="Input Description"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Thumbnail Event <span style="color:red">*</span></label>
                                    <input id="idPictureEvent" type="file" required class="form-control">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Upload Seat Mapping Image</label>
                                    <input id="idPictureSeat" type="file" required class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button id="submitBtn" type="button" class="btn btn-primary w-100 close-modal" style="background-color: #a78bfa;">Add Event</button>
                        </div>
                    </div>
                </div>
            </div>


            @if(isset($events))
                @foreach($events as $index => $event)
                    <div id="editEventModal" class="modal d-none" tabindex="-1" style="display: block;" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Event</h5>
                                    <button type="button" class="btn-close close-modal-edit" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Please provide correct and complete information in the fields below.</p>
                                    <form id="eventForm">
                                        <div class="mb-3">
                                            <label class="form-label">Title *</label>
                                            <input id="editinputTitle"  type="text" class="form-control" placeholder="Input Event Title">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Short Description (Max. 30) *</label>
                                            <input id="editinputShortDes"  type="text" class="form-control" placeholder="Input Short Description">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Date *</label>
                                            <input id="editidStartDate"  type="date" class="form-control">
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Location *</label>
                                            <input id="editidLocation"  type="text" class="form-control" placeholder="Input Event Location">
                                        </div>

                                        <div id="editticketTypesContainer">
                                            <div class="row mb-3 ticket-group">
                                                <div class="col-md-4">
                                                    <label class="form-label">Title Type *</label>
                                                    <input type="text" class="form-control edit_ticket_name"  placeholder="Input Title Type">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Price *</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">Rp</span>
                                                        <input type="number" class="form-control edit_price"   value="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Total Seat *</label>
                                                    <input type="number" class="form-control edit_total_seat"  value="0">
                                                </div>
                                                <div class="col-md-1 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btnRemoveTicketEdit">X</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mb-3">
                                            <button type="button" id="editTicket" class="btn btn-sm w-100" style="border: 1px solid #a78bfa; color: #a78bfa;">+ Add Different Ticket Type</button>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Description *</label>
                                            <textarea id="idEditDescription"  class="form-control" rows="4" placeholder="Input Description"></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thumbnail Event *</label>
                                            <input id="idEditPictureEvent"  type="file" class="form-control">
                                            <small id="previewEditPictureEvent" class="text-muted mt-1 d-block"></small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Upload Seat Mapping Image</label>
                                            <input id="idEditPictureSeat"  type="file" class="form-control">
                                            <small id="previewEditPictureSeat" class="text-muted mt-1 d-block"></small>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button id="submitBtnEdit" type="button" class="btn btn-primary w-100 close-modal-edit" style="background-color: #a78bfa;">Edit Event</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            <div id="backdrop" class="modal-backdrop fade show d-none"></div>

                <div id="eventTableContainer">
                    @include('dashboard.partials.eventTable', ['events' => $events])
                </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $('#searchBox').on('keyup', function () {
        let search = $(this).val();

        $.ajax({
            url: "{{ route('showEventTabel') }}",
            method: 'GET',
            data: { search: search },
            success: function (response) {
                $('#eventTableContainer').html(response);
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });
    $(document).ready(function () {
        $('#eventTable').DataTable({
            paging: true,
            pageLength: 10,
            searching: false,
            lengthChange: false,
            info: false
        });

        // add event
        $('#openModalBtn').click(function () {
            $('#addEventModal').removeClass('d-none').addClass('show');
            $('#backdrop').removeClass('d-none');
        });
        $('.close-modal').click(function () {
            $('#addEventModal').removeClass('show').addClass('d-none');
            $('#backdrop').addClass('d-none');
        });

        $('.editData').click(function () {
            const row = $(this).closest('tr');

            const eventId = row.data('id');  // id event
            $('#editEventModal').data('eventId', eventId);

            const title = row.data('title');
            const subtitle = row.data('subtitle');
            const location = row.data('location');
            const startDate = row.data('date');
            const description = row.data('description');
            const pictureEvent = row.data('pictureEvent');
            const pictureSeat = row.data('pictureSeats');

            const ticketNames = row.data('ticketNames') || "";
            const prices = row.data('prices') || "";
            const totalSeatsRaw = row.data('totalSeats');
            const totalSeats = (typeof totalSeatsRaw === 'string') ? totalSeatsRaw : (totalSeatsRaw != null ? totalSeatsRaw.toString() : "");

            const ticketNameArr = ticketNames.split(',');
            const priceArr = prices.split(',');
            const totalSeatArr = totalSeats.split(',');

            // Isi modal
            $('#editinputTitle').val(title);
            $('#editinputShortDes').val(subtitle);
            $('#editidStartDate').val(startDate);
            $('#editidLocation').val(location);
            $('#idEditDescription').val(description);
            $('#previewEditPictureEvent').text('File lama: ' + pictureEvent);
            $('#previewEditPictureSeat').text('File lama: ' + pictureSeat);

            $('#editticketTypesContainer').html('');
            for (let i = 0; i < ticketNameArr.length; i++) {
                $('#editticketTypesContainer').append(`
                    <div class="row mb-3 ticket-group">
                        <div class="col-md-4">
                            <input type="text" class="form-control edit_ticket_name" value="${ticketNameArr[i]}">
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control edit_price" value="${priceArr[i] || 0}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="number" class="form-control edit_total_seat" value="${totalSeatArr[i] || 0}">
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btnRemoveTicketEdit">X</button>
                        </div>
                    </div>
                `);
            }
            $('#editEventModal').removeClass('d-none').addClass('show');
            $('#backdrop').removeClass('d-none');
        });

        // Close modal
        $('.close-modal-edit').click(function () {
            $('#editEventModal').removeClass('show').addClass('d-none');
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
            const pictureEvent = $('#idPictureEvent')[0].files[0];
            const pictureSeat = $('#idPictureSeat')[0].files[0];

            if (!pictureEvent || !pictureSeat) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Gagal',
                    text: 'Mohon unggah gambar event dan seat terlebih dahulu.'
                });
                return;
            }

            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('title', $("#inputTitle").val());
            formData.append('subtitle', $("#inputShortDes").val());
            formData.append('start_date', $("#idStartDate").val());
            formData.append('location', $("#idLocation").val());
            formData.append('description', $("#idDescription").val());
            formData.append('picture_event', pictureEvent);
            formData.append('picture_seat', pictureSeat);

            $(".ticket_name").each(function (i, el) {
                formData.append(`ticket_name[${i}]`, $(el).val());
            });
            $(".price").each(function (i, el) {
                formData.append(`price[${i}]`, $(el).val());
            });
            $(".total_seat").each(function (i, el) {
                formData.append(`total_seat[${i}]`, $(el).val());
            });

            Swal.fire({
                title: 'Konfirmasi Penambahan Data',
                text: "Apakah kamu yakin ingin menambahkan data ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('addEvent') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil ditambahkan.',
                                showConfirmButton: false,
                                timer: 1500
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
                                title: 'Gagal',
                                text: xhr.responseJSON?.message || "Terjadi kesalahan saat menambahkan data."
                            });
                        }
                    });
                }
            });
        });

        $("#editTicket").on("click", function () {
            $("#editticketTypesContainer").append(`
                <div class="row mb-3 edit-ticket-group">
                    <div class="col-md-4">
                        <label class="form-label">Title Type *</label>
                        <input type="text" class="form-control edit_ticket_name" placeholder="Input Title Type">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Price *</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control edit_price" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Seat *</label>
                        <input type="number" class="form-control edit_total_seat" value="0">
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btnRemoveTicketEdit">X</button>
                    </div>
                </div>
            `);
        });
        $(document).on("click", ".btnRemoveTicketEdit", function () {
            $(this).closest(".edit-ticket-group").remove();
        });

        $("#submitBtnEdit").on("click", function (e) {
            e.preventDefault();

            let id = $('#editEventModal').data('eventId');

            console.log('ID EVENT:', id);

            let formData = new FormData();
            formData.append('_token', "{{ csrf_token() }}");
            formData.append('title', $("#editinputTitle").val());
            formData.append('subtitle', $("#editinputShortDes").val());
            formData.append('start_date', $("#editidStartDate").val());
            formData.append('location', $("#editidLocation").val());
            formData.append('description', $("#idEditDescription").val());
            if ($('#idEditPictureEvent')[0].files.length > 0) {
                formData.append('picture_event', $('#idEditPictureEvent')[0].files[0]);
            }
            if ($('#idEditPictureSeat')[0].files.length > 0) {
                formData.append('picture_seat', $('#idEditPictureSeat')[0].files[0]);
            }

            $(".edit_ticket_name").each(function (i, el) {
                formData.append(`edit_ticket_name[${i}]`, $(el).val());
            });
            $(".edit_price").each(function (i, el) {
                formData.append(`edit_price[${i}]`, $(el).val());
            });
            $(".edit_total_seat").each(function (i, el) {
                formData.append(`edit_total_seat[${i}]`, $(el).val());
            });

            for (let pair of formData.entries()) {
                console.log(pair[0]+ ': ' + pair[1]);
            }
            formData.append('_method', 'PUT');  // spoofing method PUT di Laravel

            Swal.fire({
                title: 'Apakah kamu yakin?',
                text: "Perubahan akan disimpan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#a78bfa',
                cancelButtonColor: 'transparent',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, simpan!',
                reverseButtons: true, // ⬅️ Ini yang memindahkan Cancel ke kiri
                customClass: {
                    cancelButton: 'swal-cancel-custom'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: '/editEvent/' + id,
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            });
                            if (response.status === 200) {
                                $("#editEventModal form")[0]?.reset();
                                $("#editEventModal").addClass("d-none").removeClass("show");
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
            let row = $(this).closest('tr');

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
                    $.ajax({
                        url: '/destroy/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire('Terhapus!', response.message, 'success');
                                $('#eventTable').DataTable().row(row).remove().draw();
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'Terjadi kesalahan saat menghapus.', 'error');
                        }
                    });
                }
            });
        });

    });
</script>
@endsection
