@extends('layouts.app')

@section('content')
<style>

.custom-shadow {
  box-shadow: 6px 6px 0px #000 !important;
}

</style>
<div class="container py-4">
    {{-- Logo & Tanggal --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Tikom Logo" style="height: 50px;">
        <div class="border rounded-pill px-3 py-1 fw-semibold d-flex align-items-center shadow-sm">
            <i class="bi bi-calendar me-2"></i> {{-- Gunakan Bootstrap Icons --}}
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

    <div class="d-flex gap-2 flex-wrap my-4">
        @for ($i = $currentMonth; $i <= 12; $i++)
            <a href="{{ route('selectEvent') }}?month={{ $i }}">
                <button class="btn fw-bold {{ request('month') == $i ? 'text-white' : '' }}"
                    style="background-color: #{{ sprintf('%06X', mt_rand(0xAAAAAA, 0xFFFFFF)) }}; color: black; box-shadow: 4px 4px 0px #000;">
                    {{ $monthNames[$i] }}
                </button>
            </a>
        @endfor
        @if($currentMonth > 1)
            {{-- Tambahkan juga bulan sebelumnya jika bulan sekarang adalah Januari --}}
            @for ($i = 1; $i < $currentMonth; $i++)
                <a href="{{ route('selectEvent') }}?month={{ $i }}">
                    <button class="btn fw-bold {{ request('month') == $i ? 'text-white' : '' }}"
                        style="background-color: #{{ sprintf('%06X', mt_rand(0xAAAAAA, 0xFFFFFF)) }}; color: black; box-shadow: 4px 4px 0px #000;">
                        {{ $monthNames[$i] }}
                    </button>
                </a>
            @endfor
        @endif
    </div>


    {{-- Kartu Event --}}
    <div class="row">
        @if(isset($events))
            @foreach($events as $index => $event)
                {{-- Ulangi div ini untuk setiap event --}}
                <div class="col-md-4 mb-4" >
                    <div class="card border border-dark custom-shadow" style="border-radius: 20px; color: black;">

                        <img src="{{ asset('images/konser.jpg') }}" class="card-img-top" alt="Liveground 2023" style="border-top-left-radius: 20px; border-top-right-radius: 20px;">
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
                            <a href="{{ route('detail', ['id' => $event->id]) }}" class="btn w-100 text-black fw-bold" style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">Detail</a>
                        </div>
                    </div>
                </div>
             @endforeach
        @else
            <div>Tidak ada data events</div>
        @endif
    </div>
</div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                       'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const now = new Date();
        const namaHari = hari[now.getDay()];
        const tanggal = now.getDate();
        const namaBulan = bulan[now.getMonth()];
        const tahun = now.getFullYear();

        const tanggalLengkap = `${namaHari}, ${tanggal} ${namaBulan} ${tahun}`;
        $('#tanggalLocal').text(tanggalLengkap);
    });
</script>
@endsection
