@if (\Storage::exists($url))
    <x-common.button
        tag="a"
        href="{{ $url }}"
        :label="$label"
        class="text-blue has-text-weight-medium"
        target="{{ $target ?? '_self' }}"
    />
@else
    N/A
@endif
