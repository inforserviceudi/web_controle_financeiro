
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
        $(document).delegate(".modal-call", "click", function(e) {
            var id = ($(this).data('id')) ? $(this).data('id') : '0';
            var modal = new Modal();
            var params = {id: id, _token: $("meta[name='csrf-token']").attr("content")};
            var url = ($(this).data('url')) ? $(this).data('url') : $(this).attr('href');
            var tamanho = $(this).data('width');

            modal.setParams(params);
            modal.create(tamanho);
            modal.setUrl(url);
            modal.execute();

            e.preventDefault();
        });

        $(document).on("keydown", "input, select", function(event) {
            return event.key != "Enter";
        });
    });

    @if(session('message'))
        getMessage("{{ session('tipo') }}", "{{ session('titulo') }}", "{{ session('message') }}");
    @endif
</script>
