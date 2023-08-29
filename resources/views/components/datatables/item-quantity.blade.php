<td
    class="has-text-right"
    data-sort="{{ $item['quantity'] }}"
>
    <span class="tag is-small btn-{{ $item['function'] == 'add' ? 'green' : 'purple' }} is-outline">
        <span class="icon is-medium">
            <i class="fas fa-{{ $item['function'] == 'add' ? 'plus' : 'minus' }}-circle"></i>
        </span>
        {{ $item['quantity'] }}
        {{ $item['unit_of_measurement'] }}
    </span>
</td>
