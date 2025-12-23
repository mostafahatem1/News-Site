<script>
    window.Laravel = {
        userId: {{ auth()->id() }},
         role:'user',
    };
</script>
<script src="{{ asset('/build/assets/app-CXlyt4_V.js') }}"></script>
<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('frontend/lib/easing/easing.min.js') }}"></script>
<script src="{{ asset('frontend/lib/slick/slick.min.js') }}"></script>

<!-- Page summernote scripts -->
<script src="{{ asset('frontend/vendor/summernote/summernote-bs4.min.js') }}"></script>

<!-- Page file input scripts -->
<script src="{{ asset('frontend/vendor/bootstrap-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/bootstrap-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('frontend/vendor/bootstrap-fileinput/themes/fa5/theme.min.js') }}"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Template Javascript -->
<script src="{{ asset('frontend/js/main.js') }}"></script>
<!-- Theme Toggle JavaScript -->
<script src="{{ asset('frontend/js/theme-toggle-ar.js') }}"></script>


@stack('scripts')
