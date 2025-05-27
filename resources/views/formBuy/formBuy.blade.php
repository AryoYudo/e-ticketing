@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 500px;">

    {{-- Form Pembelian --}}
    <div id="form-section">
        <div class="mb-3">
            <a href="{{ route('events') }}" class="text-dark fw-bold">
                <i class="bi bi-chevron-left me-1"></i> Kembali
            </a>
        </div>

        <div class=" mb-4">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 30px;">
        </div>

        <h3 class="fw-bold">Data Diri</h3>
        <p class="text-muted">Isi data di bawah ini dengan benar ya!</p>

        <form id="payment-form">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" placeholder="satria2323@gmail.com" required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" placeholder="Satria Syaiful Haq" required>
            </div>

            <div class="mb-3">
                <label for="nik" class="form-label">NIK</label>
                <input type="text" class="form-control" id="nik" placeholder="2123131xxxxxxxxx" required>
            </div>

            <div class="mb-3">
                <label for="birthdate" class="form-label">Tanggal Lahir</label>
                <input type="date" class="form-control" id="birthdate" required>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn w-100 fw-bold text-black" style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">
                    Bayar Sekarang
                </button>
            </div>
        </form>
    </div>

    {{-- Pembayaran Berhasil --}}
    <div id="success-section" class="d-none text-center">
        <p class="text-end fw-semibold">Sesi Berakhir : <span id="countdown">00:30</span></p>

        <div class="text-center mb-4">
            <!-- <div class="bg-light rounded-circle mx-auto" style="width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; background-color: #B487F8;">
                <i class="bi bi-check2 text-white" style="font-size: 50px;"></i>
            </div> -->
            <img src="{{ asset('images/centang.png') }}" alt="logo" style="height: 90px;" class="ms-2 mb-2">
        </div>

        <h3 class="fw-bold">Pembayaran Berhasil</h3>
        <p class="text-muted">E-ticket kamu sudah terkirim ke <strong id="emailDisplay"></strong></p>

        <div class="border rounded p-3 text-start mb-3 bg-light">
            <p class="mb-1 fw-semibold">Detail Tiket</p>
            <div class="row mb-2">
                <div class="col-5 text-muted">Nama Event</div>
                <div class="col-7 fw-semibold">Acara Konser Batam</div>
            </div>
            <div class="row mb-1">
                <div class="col-5 text-muted">Atas nama</div>
                <div class="col-7" id="nameDisplay"></div>
            </div>
            <div class="row mb-1">
                <div class="col-5 text-muted">Tanggal Lahir</div>
                <div class="col-7" id="birthdateDisplay"></div>
            </div>
            <div class="row mb-1">
                <div class="col-5 text-muted">NIK</div>
                <div class="col-7" id="nikDisplay"></div>
            </div>
            <div class="row mb-1">
                <div class="col-5 text-muted">Email</div>
                <div class="col-7" id="emailDisplayDetail"></div>
            </div>
        </div>

        <div class="alert alert-danger d-flex align-items-center small text-start" role="alert">
            <i class="bi bi-info-circle-fill me-2"></i>
            Anda dapat mencapture transaksi ini sebagai bukti pembayaran nantinya
        </div>

        <a href="{{ route('events') }}" class="btn w-100 fw-bold mt-3" style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">
            Selesai <i class="bi bi-check-circle ms-1"></i>
        </a>
    </div>

</div>

{{-- jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#payment-form').on('submit', function (e) {
        e.preventDefault();

        // Ambil data
        var email = $('#email').val();
        var name = $('#name').val();
        var nik = $('#nik').val();
        var birthdate = $('#birthdate').val();

        // Format tanggal
        var birthFormatted = new Date(birthdate).toLocaleDateString('id-ID', {
            day: '2-digit', month: 'long', year: 'numeric'
        });

        // Set data di halaman sukses
        $('#emailDisplay').text(email);
        $('#emailDisplayDetail').text(email);
        $('#nameDisplay').text(name);
        $('#nikDisplay').text(nik.slice(0, 6) + 'xxxxxxx');
        $('#birthdateDisplay').text(birthFormatted);

        // Tampilkan success, sembunyikan form
        $('#form-section').addClass('d-none');
        $('#success-section').removeClass('d-none');

        // Mulai countdown
        var seconds = 30;
        var countdown = setInterval(function () {
            seconds--;
            $('#countdown').text('00:' + seconds.toString().padStart(2, '0'));
            if (seconds <= 0) clearInterval(countdown);
        }, 1000);
    });
</script>
@endsection
