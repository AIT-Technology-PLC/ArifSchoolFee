@props(['dateColumns' => '[]', 'numericColumns' => '[]', 'hasFilter' => 'true'])

<table
    class="regular-datatable is-hoverable is-size-7 display nowrap"
    data-date="{{ $dateColumns }}"
    data-numeric="{{ $numericColumns }}"
    data-has-filter="{{ $hasFilter }}"
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
