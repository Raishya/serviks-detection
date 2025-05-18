<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', 'Laravel') }}</title>
<link rel="icon" href="{{ Asset('favicon.ico') }}" type="image/ico">
@vite(['resources/sass/app.scss', 'resources/js/app.js', 'resources/css/app.css'])
<!-- Include custom CSS for specific pages -->
@yield('custom-css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
