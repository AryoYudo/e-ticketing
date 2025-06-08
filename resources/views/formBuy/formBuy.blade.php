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

        <form id="paymentForm">
            @if($eventName)
                <input type="hidden" id="eventName" value="{{ $eventName }}">
            @endif
            @csrf
            <div class="mb-3">
                <label for="Text" class="fw-bold">Tiket</label>
                <h4 for="Text" class="fw-bold"></h4>
                @if (isset($ticketTypes))
                    <div class="ticket-option d-flex justify-content-between border-primary align-items-center p-3 border rounded-3 shadow-sm"
                    style="cursor: pointer; transition: background-color 0.3s, border-color 0.3s; background-color: #f1e7ff;">

                    <div class="d-flex align-items-center">
                        <i class="bi bi-ticket-perforated fs-3 text-purple me-3"></i>
                        <div>
                            <div class="fw-bold">Jenis Tiket</div>
                            <div class="text-muted">{{ $ticketTypes->ticket_name }}</div>
                        </div>
                    </div>
                    <div class="fw-bold fs-6 text-end">
                        Rp {{ number_format($ticketTypes->price, 0, ',', '.') }}
                    </div>
                </div>
                @endif
                {{-- <input type="Text" class="form-control" id="tiket" required> --}}
            </div>

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
                <div class="col-7 fw-semibold" id="nameEvent"></div>
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
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-h7gntHE9ugrQ_VVt"></script>
<script>
    $('#paymentForm').on('submit', function (e) {
        e.preventDefault();

        let data = {
            _token: $('input[name="_token"]').val(),
            buyer_name: $('#name').val(),
            buyer_email: $('#email').val(),
            nik: $('#nik').val(),
            birth_date: $('#birthdate').val(),
            birth_date: $('#birthdate').val(),
            eventName: $('#eventName').val(),
        };

        $.ajax({
            url: '/order_request/{{ $ticketTypes->id }}',
            method: 'POST',
            data: data,
            success: function (response) {
                if (response.status === 200) {
                    snap.pay(response.token, {
                        onSuccess: function (result) {
                            console.log("Pembayaran sukses", result);
                            $('#form-section').hide();
                            $('#success-section').removeClass('d-none');

                            $('#emailDisplay, #emailDisplayDetail').text(data.buyer_email);
                            $('#nameDisplay').text(data.buyer_name);
                            $('#nameEvent').text(data.eventName);
                            $('#birthdateDisplay').text(data.birth_date);
                            $('#nikDisplay').text(data.nik);
                        },
                        onPending: function (result) {
                            console.log("Menunggu pembayaran", result);
                        },
                        onError: function (result) {
                            console.error("Pembayaran gagal", result);
                        },
                        onClose: function () {
                            alert('Pembayaran belum selesai');
                        }
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr) {
                alert("Terjadi kesalahan");
                console.log(xhr.responseText);
            }
        });
    });
</script>

@endsection
