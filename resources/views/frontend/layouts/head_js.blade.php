<!-- Jquery, Popper, Bootstrap -->
{{-- asset function in:  vendor\laravel\framework\src\Illuminate\Foundation\helpers.php --}}

@yield('head_before_js')

<script src={{ asset('assets/all/requireJs/data/jquery.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.magnific-popup.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.scrollUp.min.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.sticky.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.form.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.validate.min.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.ajaxchimp.min.js') }}></script>
<script src={{ asset('assets/frontend/js/jquery.slicknav.min.js') }}></script>

<script src={{ asset('assets/all/requireJs/data/underscore.js') }}></script>

<script src={{ asset('assets/frontend/js/slick.min.js') }}></script>

<script src={{ asset('assets/frontend/js/popper.min.js') }}></script>

<script src={{ asset('assets/frontend/js/bootstrap.min.js') }}></script>

<script src={{ asset('assets/frontend/js/sweetalert2.all.js') }}></script>

<script src={{ asset('assets/all/requireJs/data/knockout.js') }}></script>

<script src={{ asset('assets/frontend/js/vendor/modernizr-3.5.0.min.js') }}></script>

<script src={{ asset('assets/frontend/js/owl.carousel.min.js') }}></script>

<script src={{ asset('assets/all/requireJs/require.js') }}></script>
@if (PHP_OS === 'Linux')
    <script src={{ asset('assets/all/requireJs/data/requireMainLinux.js') }}></script>
@else
    <script src={{ asset('assets/all/requireJs/data/requireMainWindown.js') }}></script>
@endif

{{-- <script src={{ asset('assets/all/requireJs/data/text.js') }}></script> --}}

@yield('head_after_js')
