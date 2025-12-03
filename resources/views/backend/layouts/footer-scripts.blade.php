<script src="{{ asset('js/app.js') }}"></script>

<!-- Bootstrap core JavaScript-->
<script src="{{ asset('backend/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('backend/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('backend/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('backend/vendor/chart.js/Chart.min.js') }}"></script>

<!-- Page level custom scripts -->
{{--<script src="{{ asset('backend/js/demo/chart-area-demo.js') }}"></script>--}}
{{--<script src="{{ asset('backend/js/demo/chart-pie-demo.js') }}"></script>--}}

<!-- Page file input scripts -->
<script src="{{ asset('backend/vendor/bootstrap-fileinput/js/plugins/piexif.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap-fileinput/js/plugins/sortable.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap-fileinput/js/fileinput.min.js') }}"></script>
<script src="{{ asset('backend/vendor/bootstrap-fileinput/themes/fa5/theme.min.js') }}"></script>

<!-- Page select2 scripts -->
<script src="{{ asset('backend/vendor/select2/js/select2.full.min.js') }}"></script>

<!-- Page summernote scripts -->
<script src="{{ asset('backend/vendor/summernote/summernote-bs4.min.js') }}"></script>

<!-- Page datepicker scripts -->
<script src="{{ asset('backend/vendor/datepicker/picker.js') }}"></script>
<script src="{{ asset('backend/vendor/datepicker/picker.date.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-user-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.closest('form').submit();
                    }
                });
            });
        });
    });
</script>

<script>
    window.Laravel = {
        adminId: {{ auth('admin')->id() }},
        role:'admin',

    };
</script>
<script src="{{ asset('/build/assets/app-CXlyt4_V.js') }}"></script>

@yield('scripts')
