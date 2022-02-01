<span>
    {{ $product }}
    @if (strlen($code))
        <span class="has-text-grey">
            ({{ $code }})
        </span>
    @endif
</span>
