<td class="has-text-right">
    <span class="tag is-small btn-green is-outline">
        <span class="icon is-medium">
            <i class="fas fa-{{ $item['function'] == 'add' ? 'plus' : 'minus' }}-circle"></i>
        </span>
        {{ $item['quantity'] }}
        {{ $item['unit_of_measurement'] }}
    </span>
</td>
