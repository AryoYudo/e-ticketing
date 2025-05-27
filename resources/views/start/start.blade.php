@extends('layouts.app')

@section('title', 'Selamat Datang di tikom')

@section('content')
<div class="min-vh-100 d-flex justify-content-center align-items-end"
     style="background-image: url('{{ asset('/images/konser.jpg') }}'); background-size: cover; background-position: center;">
  <div class="bg-white text-center w-100 py-4 px-3 rounded-top shadow-lg"
       style="box-shadow: 0 -8px 20px rgba(0,0,0,0.5);">
    <h3 class="fw-bold mb-2 d-flex align-items-center justify-content-center gap-2">
      Selamat Datang di
      <img src="{{ asset('images/logo.png') }}" alt="Logo Tikom" style="height: 32px;">
    </h3>

    <p class="text-muted mb-4">Mau datang ke konser apa hari ini?</p>

    <a href="{{ route('events') }}" class="btn w-100 fw-semibold px-4 py-2 border border-dark"
       style="background-color: #B487F8; color: black; box-shadow: 4px 4px 0px #000;">
      Pilih Ticket →
    </a>
  </div>
</div>
@endsection
