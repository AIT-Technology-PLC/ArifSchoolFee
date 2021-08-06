<section id="onHand" class="mx-3 m-lr-0">
    <div class="box radius-top-0">
        <div>
            {{ $dataTable->table() }}
        </div>
    </div>
</section>

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
