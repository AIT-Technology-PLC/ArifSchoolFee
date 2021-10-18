@if (!request()->ajax())
    @props(['buttons', 'model', 'id'])
@endif

@if ($buttons == 'all' || in_array('details', $buttons))
    <x-common.details-button route="{{ $model }}.show" :id="$id" />
@endif

@if ($buttons == 'all' || in_array('edit', $buttons))
    <x-common.edit-button route="{{ $model }}.edit" :id="$id" />
@endif

@if ($buttons == 'all' || in_array('delete', $buttons))
    <x-common.delete-button route="{{ $model }}.destroy" :id="$id" />
@endif

@if (request()->ajax())
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" defer></script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('js/caller.js') }}" defer></script>
@endif
