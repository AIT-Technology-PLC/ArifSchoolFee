@props(['route', 'id'])

<form class="is-inline delete-form"
      action="{{ route($route, $id) }}"
      method="post">
    @csrf
    @method('DELETE')
    <button data-title="Delete permanently"
            class="tag is-black has-text-white has-text-weight-medium is-pointer is-borderless">
        <x-common.icon name="fas fa-trash" />
        <span> Delete </span>
    </button>
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
                        let deleteButton = this.querySelector("button");
                        deleteButton.innerText = "Deleting ...";
                        deleteButton.disabled = true;
                        this.submit();
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
                            let deleteButton = this.querySelector("button");
                            deleteButton.innerText = "Deleting ...";
                            deleteButton.disabled = true;
                            this.submit();
                        }
                    });
                });
            }
        </script>
    @endpush
@endif
