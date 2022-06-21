@extends('layouts.app')

@section('title', 'Create Job Planner')

@section('content')
    @if (!session('report'))
        <x-common.content-wrapper>
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
                    x-init="$store.errors.setErrors({{ Js::from($errors->get('jobPlanner.*')) }})"
                >
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
                                                    x-model="jobPlanner.product_category_id"
                                                    x-on:change="Product.changeProductCategory(getSelect2(index), jobPlanner.product_id, jobPlanner.product_category_id)"
                                                />
                                            </x-forms.control>
                                            <x-forms.control class="has-icons-left is-expanded">
                                                <x-common.new-product-list
                                                    class="product-list"
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
                                                    x-text="$store.errors.getErrors(`jobPlanner.${index}.product_id`)"
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
                                                    x-text="$store.errors.getErrors(`jobPlanner.${index}.warehouse_id`)"
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
                                                    x-text="$store.errors.getErrors(`jobPlanner.${index}.bill_of_material_id`)"
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
                                                    x-text="$store.errors.getErrors(`jobPlanner.${index}.quantity`)"
                                                ></span>
                                            </x-forms.control>
                                            <x-forms.control>
                                                <x-common.button
                                                    tag="button"
                                                    type="button"
                                                    mode="button"
                                                    class="bg-green has-text-white"
                                                    x-text="Product.unitOfMeasurement(jobPlanner.product_id)"
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
        </x-common.content-wrapper>
    @else
        <div class="modal is-active">
            <div class="modal-background"></div>
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
                            <x-content.header title="Product: {{ $row->first()['product_name'] }}">
                                <h4 class="subtitle has-text-grey is-size-7">
                                    Factory: <strong>{{ $row->first()['factory_name'] }}</strong>, Quantity: <strong>{{ number_format($row->first()['quantity'], 2) }}</strong>,</br> Production Capacity: <strong>{{ number_format($row->min('production_capacity'), 2) }}</strong>
                                </h4>
                            </x-content.header>
                            <x-content.footer>
                                <x-common.bulma-table>
                                    <x-slot name="headings">
                                        <th>#</th>
                                        <th>Raw Material</th>
                                        <th class="has-text-right">Available Amount </th>
                                        <th class="has-text-right">Required Amount</th>
                                        <th class="has-text-right">Difference</th>
                                        <th class="has-text-right">Production Capacity </th>
                                    </x-slot>
                                    <x-slot name="body">
                                        @foreach ($row as $value)
                                            <tr>
                                                <td class="has-text-centered"> {{ $loop->index + 1 }} </td>
                                                <td> {{ $value['raw_material'] }}</td>
                                                <td class="has-text-right"> {{ number_format($value['available_amount'], 2) }}</td>
                                                <td class="has-text-right"> {{ number_format($value['required_amount'], 2) }}</td>
                                                <td class="{{ $value['difference'] >= 0 ? 'text-green' : 'text-purple' }} has-text-right"> {{ number_format($value['difference'], 2) }}</td>
                                                <td class="has-text-right"> {{ number_format($value['production_capacity'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                </x-common.bulma-table>
                            </x-content.footer>
                        </x-common.content-wrapper>
                    @endforeach
                </x-content.footer>
            </div>
            <button
                class="modal-close is-large"
                aria-label="close"
            ></button>
        </div>

    @endif

    @push('scripts')
        <script>
            document.addEventListener("alpine:init", () => {
                Alpine.data("jobPlannerMasterDetailForm", ({
                    jobPlanner
                }) => ({
                    jobPlanners: [],

                    async init() {
                        await Product.init();

                        if (jobPlanner) {
                            this.jobPlanners = jobPlanner;

                            await Promise.resolve(this.jobPlanners.forEach((jobPlanner) => jobPlanner.product_category_id = Product.productCategoryId(jobPlanner.product_id)))

                            await Promise.resolve($(".product-list").trigger("change", [true]));

                            return;
                        }

                        this.add();
                    },
                    add() {
                        this.jobPlanners.push({});
                    },
                    async remove(index) {
                        if (this.jobPlanners.length <= 0) {
                            return;
                        }

                        await Promise.resolve(this.jobPlanners.splice(index, 1));

                        await Promise.resolve(
                            this.jobPlanners.forEach((jobPlanner, i) => {
                                if (i >= index) {
                                    Product.changeProductCategory(this.getSelect2(i), jobPlanner.product_id, jobPlanner.product_category_id);
                                }
                            })
                        );

                        Pace.restart();
                    },
                    select2(index) {
                        let select2 = initializeSelect2(this.$el);

                        select2.on("change", (event, haveData = false) => {
                            this.jobPlanners[index].product_id = event.target.value;

                            this.jobPlanners[index].product_category_id =
                                Product.productCategoryId(
                                    this.jobPlanners[index].product_id
                                );

                            if (!haveData) {
                                Product.changeProductCategory(select2, this.jobPlanners[index].product_id, this.jobPlanners[index].product_category_id);
                            }
                        });
                    },
                    getSelect2(index) {
                        return $(".product-list").eq(index);
                    }
                }));
            });
        </script>
    @endpush
@endsection
