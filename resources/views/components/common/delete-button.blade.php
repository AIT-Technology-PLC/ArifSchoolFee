@props(['route', 'id'])

<form
    class="is-inline delete-form"
    action="{{ route($route, $id) }}"
    method="post"
>
    @csrf
    @method('DELETE')
    <x-common.button
        tag="button"
        mode="button"
        data-title="Delete permanently"
        icon="fas fa-trash"
        class="text-softblue has-text-weight-medium is-small px-2 py-0 is-transparent-color"
    />
</form>

@if (request()->ajax())
    <script type="text/javascript">
        for (const element of d.getElementsByClassName("delete-form")) {
            element.addEventListener("submit", (event) => {
                event.preventDefault();

                swal({
                    title: "Delete Permanently?",
                    text: "The selected element will be deleted permanently!",
                    icon: "error",
                    buttons: ["Not now", "Yes, Delete Forever"],
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        let deleteButton = element.querySelector("button");
                        deleteButton.innerText = "Deleting ...";
                        deleteButton.disabled = true;
                        element.submit();
                    }
                });
            });
        }
    </script>
@else
    @push('scripts')
        <script type="text/javascript">
            for (const element of d.getElementsByClassName("delete-form")) {
                element.addEventListener("submit", (event) => {
                    event.preventDefault();

                    swal({
                        title: "Delete Permanently?",
                        text: "The selected element will be deleted permanently!",
                        icon: "error",
                        buttons: ["Not now", "Yes, Delete Forever"],
                        dangerMode: true,
                    }).then((willDelete) => {
                        if (willDelete) {
                            let deleteButton = element.querySelector("button");
                            deleteButton.innerText = "Deleting ...";
                            deleteButton.disabled = true;
                            element.submit();
                        }
                    });
                });
            }
        </script>
    @endpush
@endif
