
<script src="{{ asset('vendor/jquery-browser-mobile/jquery.browser.mobile.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.js') }}"></script>
<script src="{{ asset('vendor/nanoscroller/nanoscroller.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('vendor/bootstrap-fileupload/bootstrap-fileupload.min.js') }}"></script>
<script src="{{ asset('vendor/magnific-popup/magnific-popup.js') }}"></script>
<script src="{{ asset('vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/lib/moment.min.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/fullcalendar.js') }}"></script>
<script src="{{ asset('vendor/fullcalendar/lang/pt-br.js') }}"></script>
<script src="{{ asset('vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('vendor/select2/select2_locale_pt-BR.js') }}"></script>
<script src="{{ asset('javascripts/theme.js') }}"></script>
<script src="{{ asset('javascripts/theme.custom.js') }}"></script>
<script src="{{ asset('javascripts/theme.init.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.js-single').select2({
            placeholder: "Selecione ...",
            tags: false
        });
    });
</script>
