{{-- Sidebar --}}
@php
    $currentRoute = Route::currentRouteName();
@endphp

<div class="col-md-2">
    <div class="col-md-2 position-fixed bg-white shadow-sm vh-100 d-flex flex-column p-3" style="z-index: 1000;">
        <div class="d-flex justify-content-center mb-4">
            {{-- Logo --}}
            <img src="{{ asset('images/logo.png') }}" alt="Tixboom Logo" class="mb-2" style="height: 50px;">
        </div>

        <ul class="nav nav-pills flex-column mb-auto w-100">
            <li class="nav-item mb-2">
                <a href="{{ route('dashboard') }}"
                   id="dashboardLink"
                   class="nav-link text-dark fw-semibold {{ $currentRoute == 'dashboard' ? 'active' : '' }}"
                   style="{{ $currentRoute == 'dashboard' ? 'background-color: #B487F8;' : '' }}">
                    <i class="bi bi-grid me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-4">
                <a href="{{ route('showEventTabel') }}"
                   id="listEventLink"
                   class="nav-link text-dark fw-semibold {{ $currentRoute == 'showEventTabel' ? 'active' : '' }}"
                   style="{{ $currentRoute == 'showEventTabel' ? 'background-color: #B487F8;' : '' }}">
                    <i class="bi bi-card-list me-2"></i> List Event
                </a>
            </li>
        </ul>

        <div class="mt-auto w-100 mb-4">
            <div class="d-flex align-items-center justify-content-center mb-1">
                <i class="bi bi-person-circle fs-3 me-2"></i>
                <div class="fw-semibold">Admin</div>
            </div>

            <a href="{{ route('auth.logout') }}" class="btn w-100 text-white fw-bold" style="background-color: #B487F8;">
                Log Out
            </a>
        </div>
    </div>
</div>
<!-- jQuery & SweetAlert2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Logout Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#6f42c1'
            });
        @endif
    });
</script>


