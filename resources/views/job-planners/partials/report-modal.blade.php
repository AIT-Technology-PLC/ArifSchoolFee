<div
    x-data="toggler"
    x-init="{{ !is_null(session('report')) }} && toggle"
    class="modal is-active"
    x-cloak
    x-show="!isHidden"
    x-transition
>
    <div
        class="modal-background"
        x-on:click="toggle"
    ></div>
    <div class="modal-content">
        <x-content.header title="Production Plan">
            <form
                action="{{ route('job-planners.print') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf
                <input
                    name="planner"
                    type="hidden"
                    value="{{ session('report') }}"
                >
                <x-common.button
                    tag="button"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="bg-green has-text-white is-small"
                />
            </form>
        </x-content.header>
        <x-content.footer>
            @foreach (session('report') as $row)
                <x-common.content-wrapper>
                    <x-content.header>
                        <x-slot name="header">
                            <h4 class="subtitle has-text-grey is-size-7">
                                Product: <strong>{{ $row->first()['product_name'] }}</strong>
                            </h4>
                            <h4 class="subtitle has-text-grey is-size-7">
                                Bill Of Material: <strong>{{ $row->first()['bill_of_material'] }}</strong>
                            </h4>
                            <h4 class="subtitle has-text-grey is-size-7">
                                Factory: <strong>{{ $row->first()['factory_name'] }}</strong>
                            </h4>
                            <h4 class="subtitle has-text-grey is-size-7">
                                Quantity: <strong>{{ number_format($row->first()['quantity'], 2) }} {{ $row->first()['product_unit_of_measurement'] }}</strong>
                            </h4>
                            <h4 class="subtitle has-text-grey is-size-7">
                                Production Capacity: <strong>{{ number_format($row->min('production_capacity'), 2) }} {{ $row->first()['product_unit_of_measurement'] }}</strong>
                            </h4>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <x-common.bulma-table>
                            <x-slot name="headings">
                                <th>#</th>
                                <th>Raw Material</th>
                                <th class="has-text-right">Available Amount </th>
                                <th class="has-text-right">Required Amount</th>
                                <th class="has-text-right">Difference</th>
                                <th>Unit</th>
                                <th class="has-text-right">Production Capacity </th>
                            </x-slot>
                            <x-slot name="body">
                                @foreach ($row as $value)
                                    <tr>
                                        <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                                        <td> {{ $value['raw_material'] }} </td>
                                        <td class="has-text-right"> {{ number_format($value['available_amount'], 2) }}</td>
                                        <td class="has-text-right"> {{ number_format($value['required_amount'], 2) }}</td>
                                        <td class="{{ $value['difference'] >= 0 ? 'text-green' : 'text-purple' }} has-text-right"> {{ number_format($value['difference'], 2) }}</td>
                                        <td> {{ $value['raw_material_unit_of_measurement'] }} </td>
                                        <td class="has-text-right"> {{ number_format($value['production_capacity'], 2) }} {{ $value['product_unit_of_measurement'] }}</td>
                                    </tr>
                                @endforeach
                            </x-slot>
                        </x-common.bulma-table>
                    </x-content.footer>
                </x-common.content-wrapper>
            @endforeach
        </x-content.footer>
    </div>
    <x-common.button
        tag="button"
        class="modal-close is-large"
        x-on:click="toggle"
    />
</div>
