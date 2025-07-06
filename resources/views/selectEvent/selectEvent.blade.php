@extends('layouts.app')

@section('content')

<!-- Swiper CSS (pindah ke sini dari <style>) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<style>
.custom-shadow {
  box-shadow: 6px 6px 0px #000 !important;
}

.swiper {
    padding: 40px 0;
}
.swiper-slide {
    height: auto;
}
</style>

<div class="container py-4">
    {{-- Logo & Tanggal --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Tikom Logo" style="height: 50px;">
        <div class="border rounded-pill px-3 py-1 fw-semibold d-flex align-items-center shadow-sm">
            <i class="bi bi-calendar me-2"></i>
            <span id="tanggalLocal"></span>
        </div>
    </div>

    {{-- Judul --}}
    <h2 class="fw-bold">Sekarang kamu bebas menjelajahi<br>konser yang kamu ingin datangi.</h2>
    <p class="text-muted">Yuk pilih konser nya sekarang!</p>

    @php
        use Carbon\Carbon;
        $currentMonth = Carbon::now()->month;
        $monthNames = [
            1 => 'January', 2 => 'February', 3 => 'March',
            4 => 'April', 5 => 'May', 6 => 'June',
            7 => 'July', 8 => 'August', 9 => 'September',
            10 => 'October', 11 => 'November', 12 => 'December',
        ];
    @endphp

    {{-- Filter Bulan --}}
    <div class="d-flex gap-2 flex-wrap my-4">
        @for ($i = $currentMonth; $i <= 12; $i++)
            <a href="{{ route('events') }}?month={{ $i }}">
                <button class="btn fw-bold {{ request('month') == $i ? 'text-white' : '' }}"
                    style="background-color: #{{ sprintf('%06X', mt_rand(0xAAAAAA, 0xFFFFFF)) }}; color: black; box-shadow: 4px 4px 0px #000;">
                    {{ $monthNames[$i] }}
                </button>
            </a>
        @endfor
        @if($currentMonth > 1)
            @for ($i = 1; $i < $currentMonth; $i++)
                <a href="{{ route('events') }}?month={{ $i }}">
                    <button class="btn fw-bold {{ request('month') == $i ? 'text-white' : '' }}"
                        style="background-color: #{{ sprintf('%06X', mt_rand(0xAAAAAA, 0xFFFFFF)) }}; color: black; box-shadow: 4px 4px 0px #000;">
                        {{ $monthNames[$i] }}
                    </button>
                </a>
            @endfor
        @endif
    </div>

    {{-- Swiper Carousel --}}
    <div class="swiper mySwiper">
        <div class="swiper-wrapper">
            @if(isset($events))
                @foreach($events as $index => $event)
                    <div class="swiper-slide">
                        <div class="card border border-dark custom-shadow" style="border-radius: 20px; color: black;">
                            <img src="{{ asset('storage/' . $event->picture_event) }}"
                                class="card-img-top"
                                alt="Liveground 2023"
                                style="border-top-left-radius: 20px; border-top-right-radius: 20px; height: 200px; object-fit: cover; width: 100%;">

                            <div class="card-body">
                                <small class="text-uppercase fw-bold text-muted">Official Ticketing Partner</small>
                                <img src="{{ asset('images/logo.png') }}" alt="logo" style="height: 20px;" class="ms-2 mb-2">
                                <h5 class="fw-bold">{{ $event->title }}</h5>
                                <p class="text-muted mb-1">{{ $event->location }}</p>
                                <p class="mb-2">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $event->start_date }}
                                </p>
                                <p class="fw-semibold mb-1">Start From</p>
                                <p class="fw-bold fs-5">Rp {{ number_format($event->min_price, 0, ',', '.') }}</p>
                                <a href="{{ route('detail', ['id' => $event->id]) }}" class="btn w-100 text-black fw-bold"
                                    style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="swiper-slide">
                    <div class="card p-4 text-center">Tidak ada data events</div>
                </div>
            @endif
        </div>
    </div>

</div>

@endsection

@section('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
    $(document).ready(function () {
        // Tampilkan tanggal lokal
        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const now = new Date();
        const tanggalLengkap = `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;
        $('#tanggalLocal').text(tanggalLengkap);

        // Inisialisasi Swiper
        new Swiper(".mySwiper", {
            slidesPerView: 3,
            spaceBetween: 30,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                0: {
                    slidesPerView: 1
                },
                768: {
                    slidesPerView: 2
                },
                992: {
                    slidesPerView: 3
                }
            }
        });
    });
</script>
@endsection
