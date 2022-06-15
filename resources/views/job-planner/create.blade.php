@extends('layouts.app')

@section('title', 'Create Job Planner')
@section('content')
    <x-common.content-wrapper>
        @if (session('message'))
            @foreach (session('message') as $row)
                <h3>
                    <p class="pr-4">Product: {{ $row->first()['product_name'] }}</p> Factory: {{ $row->first()['factory_name'] }}
                </h3>
                <h3>Quantity: {{ $row->first()['quantity'] }}, Production Capability: {{ $row->min('production_capability') }}</h3>
                <table class="table">
                    <th>Raw Material</th>
                    <th>Required Amount</th>
                    <th>Available Amount </th>
                    <th>Production Capability </th>
                    <th>Status </th>
                    <tbody>
                        @foreach ($row as $value)
                            <tr>
                                <td> {{ $value['raw_material'] }}</td>
                                <td> {{ $value['required_amount'] }}</td>
                                <td> {{ $value['available_amount'] }}</td>
                                <td> {{ $value['production_capability'] }}</td>
                                <td> {{ $value['status'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <x-content.header title="New Job Planner" />
            <form
                id="formOne"
                action="{{ route('job-planners.store') }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main
                    x-data="jobPlannerMasterDetailForm({{ Js::from(session()->getOldInput()) }})"
                    x-init="setErrors({{ json_encode($errors->get('jobPlanner.*')) }})"
                >
                    <x-common.fail-message :message="session('failedMessage')" />
                    <template
                        x-for="(jobPlanner, index) in jobPlanners"
                        x-bind:key="index"
                    >
                        <div class="mx-3">
                            <x-forms.field class="has-addons mb-0 mt-5">
                                <x-forms.control>
                                    <span
                                        class="tag bg-green has-text-white is-medium is-radiusless"
                                        x-text="`Item ${index + 1}`"
                                    ></span>
                                </x-forms.control>
                                <x-forms.control>
                                    <x-common.button
                                        tag="button"
                                        mode="tag"
                                        type="button"
                                        class="bg-lightgreen has-text-white is-medium is-radiusless"
                                        x-on:click="remove(index)"
                                    >
                                        <x-common.icon
                                            name="fas fa-times-circle"
                                            class="text-green"
                                        />
                                    </x-common.button>
                                </x-forms.control>
                            </x-forms.field>
                            <div class="box has-background-white-bis radius-top-0">
                                <div class="columns is-marginless is-multiline">
                                    <div class="column is-6">
                                        <x-forms.label x-bind:for="`jobPlanner[${index}][product_id]`">
                                            Product <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control
                                                class="has-icons-left"
                                                style="width: 30%"
                                            >
                                                <x-common.category-list
                                                    x-model="selectedCategory"
                                                    x-on:change="getProductsByCategory"
                                                />
                                            </x-forms.control>
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-common.product-list
                                                    x-bind:id="`jobPlanner[${index}][product_id]`"
                                                    x-bind:name="`jobPlanner[${index}][product_id]`"
                                                    x-model="jobPlanner.product_id"
                                                    x-init="select2(index)"
                                                />
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-small is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`jobPlanner.${index}.product_id`)"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.label x-bind:for="`jobPlanner[${index}][warehouse_id]`">
                                            Factory <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    x-bind:id="`jobPlanner[${index}][warehouse_id]`"
                                                    x-bind:name="`jobPlanner[${index}][warehouse_id]`"
                                                    x-model="jobPlanner.warehouse_id"
                                                >
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-small is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`jobPlanner.${index}.warehouse_id`)"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div class="column is-6">
                                        <x-forms.label x-bind:for="`jobPlanner[${index}][bill_of_material_id]`">
                                            Bill Of Material <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-forms.select
                                                    class="is-fullwidth"
                                                    x-bind:id="`jobPlanner[${index}][bill_of_material_id]`"
                                                    x-bind:name="`jobPlanner[${index}][bill_of_material_id]`"
                                                    x-model="jobPlanner.bill_of_material_id"
                                                >
                                                    @foreach ($billOfMaterials as $billOfMaterial)
                                                        <option value="{{ $billOfMaterial->id }}">
                                                            {{ $billOfMaterial->name }}
                                                        </option>
                                                    @endforeach
                                                </x-forms.select>
                                                <x-common.icon
                                                    name="fas fa-th"
                                                    class="is-small is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`jobPlanner.${index}.bill_of_material_id`)"
                                                ></span>
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                    <div
                                        class="column is-6"
                                        x-data="productDataProvider(jobPlanner.product_id)"
                                        x-init="getProduct(jobPlanner.product_id) && $watch(`jobPlanner.product_id`, (value) => getProduct(value))"
                                    >
                                        <x-forms.label x-bind:for="`jobPlanner[${index}][quantity]`">
                                            Quantity <sup class="has-text-danger">*</sup>
                                        </x-forms.label>
                                        <x-forms.field class="has-addons">
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-forms.input
                                                    x-bind:id="`jobPlanner[${index}][quantity]`"
                                                    x-bind:name="`jobPlanner[${index}][quantity]`"
                                                    x-model="jobPlanner.quantity"
                                                />
                                                <x-common.icon
                                                    name="fas fa-balance-scale"
                                                    class="is-large is-left"
                                                />
                                                <span
                                                    class="help has-text-danger"
                                                    x-text="getErrors(`jobPlanner.${index}.quantity`)"
                                                ></span>
                                            </x-forms.control>
                                            <x-forms.control>
                                                <x-common.button
                                                    tag="button"
                                                    type="button"
                                                    mode="button"
                                                    class="bg-green has-text-white"
                                                    x-text="product.unit_of_measurement"
                                                />
                                            </x-forms.control>
                                        </x-forms.field>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <x-common.button
                        tag="button"
                        type="button"
                        mode="button"
                        label="Add More Item"
                        class="bg-purple has-text-white is-small ml-3 mt-6"
                        x-on:click="add"
                    />
                </x-content.main>

                <x-content.footer>
                    <x-common.save-button />
                </x-content.footer>
            </form>
        @endif
    </x-common.content-wrapper>
@endsection
