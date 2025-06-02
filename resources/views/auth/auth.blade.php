@extends('layouts.app')

@section('content')
<style>
    html, body {
        height: 100%;
        overflow: hidden;
    }
</style>

<div class="container-fluid h-100">
    <div class="row justify-content-center align-items-center h-100">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                {{-- Logo --}}
                <div class="mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Tixboom Logo" style="height: 40px;">
                </div>

                {{-- Heading --}}
                <h3 class="fw-bold mb-2">Welcome Back 👋</h3>
                <p class="text-muted mb-4">Sign in to manage and personalize your Tixboom events.</p>

                {{-- Form Login --}}
                <form id="formLogin">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Username</label>
                        <input type="email" name="email" id="emailAdmin" class="form-control" placeholder="your@email.com" required>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Fill your password here" required>
                    </div>

                    <button type="submit" id="btnLogin" class="btn w-100 text-white fw-bold" style="background-color: #B487F8; box-shadow: 4px 4px 0px #000;">
                        Login
                    </button>
                </form>
            </div>
        </div>

        {{-- Image Right Side --}}
        <div class="col-md-6 text-center d-none d-md-block">
            <img src="{{ asset('images/login.png') }}" alt="Secure Login" class="img-fluid" style="max-width: 400px;">
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    const formLogin = $('#formLogin');

    formLogin.submit(function(e) {
        e.preventDefault();

        const btnLogin = $('#btnLogin');
        btnLogin.prop('disabled', true).text('Logging in...');

        const emailAdmin = $('#emailAdmin').val();
        const password = $('#password').val();

        console.log(emailAdmin, password);
        $.ajax({
            url: '{{ route('auth.login') }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                emailAdmin: emailAdmin,
                password: password,
            },
            dataType: 'json',
            success: function(res) {
                if (res.status === 200) {
                    formLogin[0].reset();
                    window.location = '{{ route('dashboard') }}';
                }
            },
            error: function(res) {
                let message = res.responseJSON?.MESSAGE || 'Login failed';
                alert(message);
                btnLogin.prop('disabled', false).text('Login');
            }
        });
    });
</script>
@endsection
