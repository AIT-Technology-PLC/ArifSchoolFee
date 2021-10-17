<a href="{{ route("{$model}.edit", $id) }}" data-title="Modify Product Data">
    <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-pen-square"></i>
        </span>
        <span>
            Edit
        </span>
    </span>
</a>
<x-common.delete-button :model="$model" :id="$id" />


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" defer></script>
<script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/caller.js') }}" defer></script>
