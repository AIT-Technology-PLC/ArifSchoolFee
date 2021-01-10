<div id="sivModal{{ $siv->id }}" class="modal is-fullwidth">
    <div class="modal-background"></div>
    <div class="modal-card">
        <header class="modal-card-head bg-green">
            <p class="modal-card-title has-text-white">SIV No: {{ $siv->code }}</p>
            <button class="delete is-large" aria-label="close" data-id="{{ $siv->id }}"></button>
        </header>
        <section class="modal-card-body">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-purple has-text-left"><abbr> Product </abbr></th>
                            <th class="has-text-left"><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Quantity </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($siv->sivDetails as $sivDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-uppercase has-text-left">
                                    <span class="tag bg-purple has-text-white">
                                        {{ $sivDetail->product->name }}
                                    </span>
                                </td>
                                <td class="has-text-left">
                                    {!! nl2br(e($sivDetail->description)) !!}
                                </td>
                                <td class="has-text-right">
                                    {{ $sivDetail->quantity }}
                                    {{ $sivDetail->product->unit_of_measurement }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
