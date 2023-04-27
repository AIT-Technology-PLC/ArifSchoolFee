@extends('layouts.app')

@section('title', $pad->name)

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column p-lr-0">
            <x-common.total-model
                model="{{ $pad->abbreviation }}"
                :amount="$transactions->count()"
                icon="{{ $pad->icon }}"
            />
        </div>
        @if ($pad->isInventoryOperationAdd())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalAdded']"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Added"
                />
            </div>
        @elseif ($pad->isInventoryOperationSubtract())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalSubtracted']"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Subtracted"
                />
            </div>
        @endif

        @if ($pad->isApprovable())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalApproved']"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Approved"
                />
            </div>
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalNotApproved']"
                    border-color="#863d63"
                    text-color="text-purple"
                    label="Waiting Approval"
                />
            </div>
        @endif
    </div>

    <x-common.content-wrapper>
        <x-content.header title="{{ $pad->name }}">
            @canpad('Create', $pad)
            <x-common.button
                tag="a"
                href="{{ route('pads.transactions.create', $pad->id) }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create {{ str()->singular($pad->name) }}"
                class="btn-green is-outlined is-small"
            />
            @endcanpad
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            @if (
                $padStatuses->isNotEmpty() ||
                    authUser()->getAllowedWarehouses('transactions')->isNotEmpty() ||
                    !$pad->isInventoryOperationNone() ||
                    $pad->isApprovable())
                <x-datatables.filter filters="'branch', 'status', 'inventoryStatus'">
                    <div class="columns is-marginless is-vcentered">
                        @if (authUser()->getAllowedWarehouses('transactions')->isNotEmpty())
                            <div class="column is-3 p-lr-0 pt-0">
                                <x-forms.field class="has-text-centered">
                                    <x-forms.control>
                                        <x-forms.select
                                            id=""
                                            name=""
                                            class="is-size-7-mobile is-fullwidth"
                                            x-model="filters.branch"
                                            x-on:change="add('branch')"
                                        >
                                            <option
                                                disabled
                                                selected
                                                value=""
                                            >
                                                Branches
                                            </option>
                                            <option value="all"> All </option>
                                            @foreach (authUser()->getAllowedWarehouses('transactions') as $warehouse)
                                                <option value="{{ $warehouse->id }}"> {{ $warehouse->name }} </option>
                                            @endforeach
                                        </x-forms.select>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                        @if ($padStatuses->isNotEmpty())
                            <div class="column is-3 p-lr-0 pt-0">
                                <x-forms.field class="has-text-centered">
                                    <x-forms.control>
                                        <x-forms.select
                                            id=""
                                            name=""
                                            class="is-size-7-mobile is-fullwidth"
                                            x-model="filters.status"
                                            x-on:change="add('status')"
                                        >
                                            <option
                                                disabled
                                                selected
                                                value=""
                                            >
                                                Statuses
                                            </option>
                                            <option value="all"> All </option>
                                            @foreach ($padStatuses as $padStatus)
                                                <option value="{{ str()->lower($padStatus->name) }}"> {{ $padStatus->name }} </option>
                                            @endforeach
                                        </x-forms.select>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                        @if (!$pad->isInventoryOperationNone() || $pad->isApprovable())
                            <div class="column is-3 p-lr-0 pt-0">
                                <x-forms.field class="has-text-centered">
                                    <x-forms.control>
                                        <x-forms.select
                                            id=""
                                            name=""
                                            class="is-size-7-mobile is-fullwidth"
                                            x-model="filters.inventoryStatus"
                                            x-on:change="add('inventoryStatus')"
                                        >
                                            <option
                                                disabled
                                                selected
                                                value=""
                                            >
                                                Statuses
                                            </option>
                                            <option value="all"> All </option>
                                            <option value="waiting approval"> Waiting Approval </option>
                                            <option value="approved"> Approved </option>
                                            @if ($pad->isInventoryOperationAdd())
                                                <option value="added"> Added </option>
                                            @elseif($pad->isInventoryOperationSubtract())
                                                <option value="subtracted"> Subtracted </option>
                                            @endif
                                        </x-forms.select>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                    </div>
                </x-datatables.filter>
            @endif

            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
