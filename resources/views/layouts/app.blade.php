<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.header')
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
    <style>
        .sidebar-profile-img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
            display: block;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" id="sidebarToggle" type="button">
                    <img src="{{ asset('iconApp.svg') }}" width="30" height="30" alt="logo">
                </button>
                <div class="sidebar-logo">
                    <a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>
            </div>
            <div class="sidebar-profile d-flex justify-content-center align-items-center" style="height:120px;">
                <div style="width:100px;height:100px;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://via.placeholder.com/100' }}"
                        class="border border-5 border-primary sidebar-profile-img"
                        alt="Profile Picture">
                </div>
            </div>
            <ul class="sidebar-nav">
                @auth
                    @if (Auth::user()->hasRole('user'))
                        <li class="sidebar-item">
                            <a href="{{ route('home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->hasRole('dokter'))
                        <li class="sidebar-item">
                            <a href="{{ route('dokter.home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->hasRole('admin'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="lni lni-agenda"></i>
                            <span>Task</span>
                        </a>
                    </li> --}}
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dokter'))
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                                <i class="lni lni-protection"></i>
                                <span>Pasien</span>
                            </a>
                            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                                <li class="sidebar-item">
                                    <a href="{{ route('addPasien') }}" class="sidebar-link">Tambah Pasien</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('diagnosa.arsip') }}" class="sidebar-link">Data Pasien</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('kolposkop') }}" class="sidebar-link">Kamera Cerviscope</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth
                {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi" aria-expanded="false" aria-controls="multi">
                        <i class="lni lni-layout"></i>
                        <span>Multi Level</span>
                    </a>
                    <ul id="multi" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#multi-two" aria-expanded="false" aria-controls="multi-two">
                                Two Links
                            </a>
                            <ul id="multi-two" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 1</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Notification</span>
                    </a>
                </li> --}}
                <li class="sidebar-item">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Setting</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                @auth
                    <a href="{{ route('logout') }}" class="sidebar-link"
                        onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                        <i class="lni lni-exit"></i>
                        <span>{{ __('Logout') }}</span>
                    </a>
                @endauth
            </div>
        </aside>

        <div class="top-nav d-lg-none">
            <button class="toggle-btn" id="hamBurger" type="button">
                <i class="lni lni-menu"></i>
            </button>
            <div class="logo">
                <a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a>
            </div>
            <div class="user-info">
                @auth
                    <button id="user-info-btn" class="btn btn-link dropdown-toggle" id="userDropdownMobile"
                        data-bs-toggle="dropdown" aria-expanded="false"><img
                            src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://via.placeholder.com/30' }}"
                            width="30" height="30" class="rounded-circle me-2" alt="Profile Picture">
                        <span>{{ Auth::user()->name }}</span></button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownMobile">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Edit Profile') }}</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </a></li>
                    </ul>
                @endauth
            </div>
        </div>
        <div class="mobile-menu d-lg-none">
            <ul class="sidebar-nav">
                @auth
                    @if (Auth::user()->hasRole('user'))
                        <li class="sidebar-item">
                            <a href="{{ route('home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->hasRole('dokter'))
                        <li class="sidebar-item">
                            <a href="{{ route('dokter.home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @elseif (Auth::user()->hasRole('admin'))
                        <li class="sidebar-item">
                            <a href="{{ route('admin.home') }}" class="sidebar-link">
                                <i class="lni lni-user"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif
                    {{-- <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="lni lni-agenda"></i>
                            <span>Task</span>
                        </a>
                    </li> --}}
                    @if (Auth::user()->hasRole('admin') || Auth::user()->hasRole('dokter'))
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                                data-bs-target="#auth-mobile" aria-expanded="false" aria-controls="auth-mobile">
                                <i class="lni lni-protection"></i>
                                <span>Pasien</span>
                            </a>
                            <ul id="auth-mobile" class="sidebar-dropdown list-unstyled collapse"
                                data-bs-parent="#sidebar">
                                <li class="sidebar-item">
                                    <a href="{{ route('addPasien') }}" class="sidebar-link">Tambah Pasien</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('diagnosa.arsip') }}" class="sidebar-link">Data Pasien</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="{{ route('kolposkop') }}" class="sidebar-link">Kamera Cerviscope</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                @endauth
                {{-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#multi-mobile" aria-expanded="false" aria-controls="multi-mobile">
                        <i class="lni lni-layout"></i>
                        <span>Multi Level</span>
                    </a>
                    <ul id="multi-mobile" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse"
                                data-bs-target="#multi-two-mobile" aria-expanded="false"
                                aria-controls="multi-two-mobile">
                                Two Links
                            </a>
                            <ul id="multi-two-mobile" class="sidebar-dropdown list-unstyled collapse">
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 1</a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="#" class="sidebar-link">Link 2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Notification</span>
                    </a>
                </li> --}}
                <li class="sidebar-item">
                    <a href="{{ route('profile.edit') }}" class="sidebar-link">
                        <i class="lni lni-cog"></i>
                        <span>Setting</span>
                    </a>
                </li>
            </ul>
            {{-- <div class="sidebar-footer">
                <a href="{{ route('logout') }}" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>{{ __('Logout') }}</span>
                </a>
            </div> --}}
        </div>
        <main class="main p-3">
            @yield('content')
            @include('partials.footer')
        </main>
    </div>

    <!-- Ensure JavaScript is at the end of the body -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const hamBurger = document.getElementById("hamBurger");
            const mobileMenu = document.querySelector(".mobile-menu");
            const sidebarToggle = document.getElementById("sidebarToggle");
            const sidebar = document.getElementById("sidebar");

            hamBurger.addEventListener("click", function() {
                if (mobileMenu.classList.contains("show")) {
                    mobileMenu.classList.remove("show");
                    setTimeout(() => {
                        mobileMenu.style.display = "none";
                    }, 500); // Duration of the transition
                } else {
                    mobileMenu.style.display = "block";
                    setTimeout(() => {
                        mobileMenu.classList.add("show");
                    }, 10); // Short delay to trigger the transition
                }
            });

            // sidebarToggle.addEventListener("click", function() {
            //     sidebar.classList.toggle("expand");
            // });

            // Bootstrap collapse for dropdowns
            // var dropdowns = document.querySelectorAll('[data-bs-toggle="collapse"]');
            // dropdowns.forEach(function(dropdown) {
            //     dropdown.addEventListener('click', function() {
            //         var target = document.querySelector(dropdown.getAttribute('data-bs-target'));
            //         target.classList.toggle('show');
            //     });
            // });
        });
    </script>
</body>

</html>
