<span>
    {{ $product }}
    @if (strlen($code))
        <span class='has-text-grey has-has-text-weight-bold'> - {{ $code }} </span>
    @endif
</span>
