{{-- <footer id="kaki" class="container justify-content-center align-items-center">
    <div class="container">
        <div class="p-kaki">
            <p class="text-center">&copy; {{ date('Y') }} Si Serviks. All rights reserved.</p>
        </div>
        <!-- Tambahkan konten footer Anda di sini -->
    </div>
</footer> --}}

{{-- <!-- Include Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script> --}}
@section('custom css')
    <style>
        footer {}
    </style>
@endsection
<footer class="d-block text-center text-secondary text-lg-start mt-auto">
    <div class="text-center p-3">
        &copy; {{ date('Y') }} Si Serviks. All rights reserved:
        <a class="text-secondary" href="https://yourapplication.com/">siserviks.com</a>
    </div>
</footer>
