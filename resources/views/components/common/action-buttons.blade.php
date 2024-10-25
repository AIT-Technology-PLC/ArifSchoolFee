@if (!request()->ajax())
    @props(['buttons', 'model', 'id'])
@endif

@if ($buttons == 'all' || in_array('details', $buttons))
    <x-common.details-button
        route="{{ $model }}.show"
        :id="$id"
    />
@endif

@if ($buttons == 'all' || in_array('edit', $buttons))
    <x-common.edit-button
        route="{{ $model }}.edit"
        :id="$id"
    />
@endif

@if ($buttons == 'all' || in_array('delete', $buttons))
    <x-common.delete-button
        route="{{ $model }}.destroy"
        :id="$id"
    />
@endif