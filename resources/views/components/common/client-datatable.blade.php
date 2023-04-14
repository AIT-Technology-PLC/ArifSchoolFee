@props([
    'dateColumns' => '[]',
    'numericColumns' => '[]',
    'hasFilter' => 'true',
    'hasLengthChange' => 'true',
    'pagingType' => 'simple_numbers',
    'lengthMenu' => '[10, 25, 50, 75, 100]',
])

<table
    class="regular-datatable is-hoverable is-size-7 display nowrap"
    data-date="{{ $dateColumns }}"
    data-numeric="{{ $numericColumns }}"
    data-has-filter="{{ $hasFilter }}"
    data-has-length-change="{{ $hasLengthChange }}"
    data-paging-type="{{ $pagingType }}"
    data-length-menu="{{ $lengthMenu }}"
    {{ $attributes->whereStartsWith('x-') }}
>
    <thead>
        <tr>
            {{ $headings }}
        </tr>
    </thead>
    <tbody>
        {{ $body }}
    </tbody>
</table>
