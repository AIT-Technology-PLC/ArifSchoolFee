@livewireScripts

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous"
></script>

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/axios@0.21.0/dist/axios.min.js"
    integrity="sha256-OPn1YfcEh9W2pwF1iSS+yDk099tYj+plSrCS6Esa9NA="
    crossorigin="anonymous"
></script>

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"
></script>

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"
></script>

{{-- Datatable Library --}}

<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"
></script>

<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"
></script>

<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"
></script>

<script
    type="text/javascript"
    src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"
></script>

<script
    type="text/javascript"
    src="{{ asset('js/datatables-plugins.js') }}"
></script>
{{-- Other Library --}}

<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
    integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
    crossorigin="anonymous"
    defer
></script>

<script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.3/pace.min.js"
    data-pace-options='{ "ajax": false }'
></script>

<script
    type="text/javascript"
    src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"
></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- Local Assets --}}

<script
    type="text/javascript"
    src="{{ asset('js/store.js') }}"
></script>

<script
    type="text/javascript"
    src="{{ asset('js/app.js') }}"
></script>

<script
    type="text/javascript"
    src="{{ asset('js/template.js') }}"
></script>

<script
    type="text/javascript"
    src="{{ asset('js/caller.js') }}"
></script>

@env('production')
{{-- Global site tag (gtag.js) - Google Analytics --}}

<script
    async
    src="https://www.googletagmanager.com/gtag/js?id=UA-212816628-1"
></script>

<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'UA-212816628-1');

    gtag('config', 'G-8L36JNQNY9');
</script>
@endenv

@stack('scripts')
