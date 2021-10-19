@props(['headings', 'body'])

<div class="table-container">
    <table class="table is-hoverable is-fullwidth is-size-7">
        <thead>
            <tr>
                {{ $headings }}
            </tr>
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>
</div>
