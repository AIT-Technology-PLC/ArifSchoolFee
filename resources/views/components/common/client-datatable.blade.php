@props(['dateColumns' => '[]', 'numericColumns' => '[]'])

<table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="{{ $dateColumns }}" data-numeric="{{ $numericColumns }}">
    <thead>
        <tr>
            {{ $headings }}
        </tr>
    </thead>
    <tbody>
        {{ $body }}
    </tbody>
</table>
