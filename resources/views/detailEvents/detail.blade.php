@extends('layouts.app')

@section('content')
<style>
    .custom-tabs .nav-link {
        color: #333;
        border: none;
        background: none;
        position: relative;
        transition: color 0.2s ease-in-out;
    }

    .custom-tabs .nav-link.active {
        color: #B487F8;
        font-weight: 600;
    }

    .custom-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        left: 0;
        right: 0;
        bottom: -4px;
        height: 3px;
        background-color: #B487F8;
        border-radius: 2px;
    }

    .custom-tabs .nav-link:hover {
        color: #B487F8;
    }
</style>

<div class="container py-4">

    {{-- Tombol Kembali --}}
    <div class="mb-3">
        <a href="{{ route('events') }}" class="text-dark fw-bold"><i class="bi bi-chevron-left me-1"></i> Kembali</a>
    </div>
    @if(isset($event))
        {{-- Gambar & Informasi Utama --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
            <img src="{{ asset('images/background.png') }}" class="card-img-top" style="border-radius: 20px;">
        </div>

        {{-- Judul --}}
        <h2 class="fw-bold">{{ $event->title }}</h2>
        <p class="text-muted">{{ $event->subtitle }}</p>

        {{-- Info Waktu dan Lokasi --}}
        <div class="d-flex align-items-center text-muted mb-3">
            <i class="bi bi-calendar-event me-2"></i>{{ $event->start_date }}
            <span class="mx-3">|</span>
            <i class="bi bi-geo-alt me-1"></i>{{ $event->location }}
        </div>

        {{-- Tab --}}
        <ul class="nav custom-tabs mb-3 " id="eventTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-semibold" data-bs-target="#desc" type="button" role="tab">Deskripsi</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-target="#ticket" type="button" role="tab">Jenis Tiket</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-semibold" data-bs-target="#map" type="button" role="tab">Pemetaan</button>
            </li>
        </ul>



        <div class="tab-content" id="eventTabContent">
            <div class="tab-pane show active" id="desc" role="tabpanel">
                <p>{{ $event->description }}</p>
            </div>
            <div class="tab-pane d-none" id="ticket" role="tabpanel">
                <div class="d-flex flex-column gap-3">
                    @foreach($ticketTypes as $index => $ticket)
                    {{-- Tiket 1 --}}
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-ticket-perforated fs-3 text-purple me-3"></i>
                                <div>
                                    <div class="fw-bold">Jenis Tiket {{ $index + 1}}</div>
                                    <small class="text-muted">{{ $ticket->ticket_name}}</small>
                                </div>
                            </div>
                            <div class="fw-bold fs-6 text-end">
                                Rp  {{ number_format($ticket->price, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane d-none" id="map" role="tabpanel">
                <p>Pemetaan lokasi tempat duduk atau venue akan ditampilkan di sini.</p>
            </div>
        </div>
    @else
    @endif

    {{-- Tombol Beli --}}
    <div class="mt-4">
        <a href="{{ route('buy') }}" class="btn w-100 fw-bold text-black" style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">
            <i class="bi bi-ticket-perforated me-2"></i> Beli Tiket
        </a>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(function() {
        $('#eventTab button').on('click', function () {
            const target = $(this).data('bs-target');

            // Sembunyikan semua tab-pane
            $('.tab-pane').addClass('d-none').removeClass('show active');

            // Tampilkan tab yang diklik
            $(target).removeClass('d-none').addClass('show active');

            // Update tab-button aktif
            $('#eventTab button').removeClass('active');
            $(this).addClass('active');
        });
    });
</script>

@endsection
