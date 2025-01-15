@props(['icon', 'data' => 'N/A', 'label', 'type' => 'short'])

@if ($type == 'long')
    <div class="columns is-marginless is-vcentered is-multiline is-mobile text-softblue">
        <div class="column ml-5 mr-1">
            <div class="has-text-weight-bold"> {{ $label }} </div>
            <div class="is-size-7 mt-3"> {!! is_null($data) ? 'N/A' : nl2br($data) !!} </div>
        </div>
    </div>
@else
    <div class="columns is-marginless is-vcentered is-mobile text-softblue">
        <div class="column is-1 ml-5 mr-1">
            <x-common.icon
                name="{{ $icon }}"
                class="is-size-4"
            />
        </div>
        <div class="column m-lr-20">
            <div class="is-uppercase is-size-7 has-text-weight-bold"> {{ $data ?? 'N/A' }} </div>
            <div class="is-size-7"> {{ $label }} </div>
        </div>
    </div>
@endif
