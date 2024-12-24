@extends('layouts.app')

@section('title', 'Edit Account')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                    <x-common.icon name="fas fa-pen" />
                    <span>
                        Edit Account
                    </span>
                </span>
            </x-slot>
        </x-content.header>
        <form
            id="formOne"
            action="{{ route('accounts.update', $account->id) }}"
            method="post"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf
            @method('PATCH')
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                <div class="columns is-marginless is-multiline is-mobile">
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.label>
                            Account Name <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.field>
                            <x-forms.control class="has-icons-left">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="account_type"
                                    name="account_type"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Account Type
                                    </option>
                                    @if (old('account_type', $account->account_type))
                                        <option
                                            value="{{ old('account_type', $account->account_type) }}"
                                            selected
                                        >
                                            {{ old('account_type', $account->account_type) }}
                                        </option>
                                    @endif
                                    @include('lists.banks')
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="account_type" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="account_number">
                                 Account Number <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="account_number"
                                    id="account_number"
                                    placeholder="Acccount Number"
                                    value="{{ $account->account_number ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-hashtag"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="account_number" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="account_holder">
                                 Account Holder <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left">
                                <x-forms.input
                                    type="text"
                                    name="account_holder"
                                    id="account_holder"
                                    placeholder="Acccount Holder"
                                    value="{{ $account->account_holder ?? '' }}"
                                />
                                <x-common.icon
                                    name="fas fa-user"
                                    class="is-large is-left"
                                />
                                <x-common.validation-error property="account_holder" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-6-mobile is-6-tablet is-6-desktop">
                        <x-forms.field>
                            <x-forms.label for="is_active">
                                Status <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.control class="has-icons-left ">
                                <x-forms.select
                                    class="is-fullwidth"
                                    id="is_active"
                                    name="is_active"
                                >
                                    <option
                                        disabled
                                        selected
                                    >
                                        Select Status
                                    </option>
                                    <option
                                        value="1"
                                        @selected(old('is_active', $account->is_active) == '1')
                                    >
                                        Active
                                    </option>
                                    <option
                                        value="0"
                                        @selected(old('is_active', $account->is_active) == '0')
                                    >
                                        Not Active
                                    </option>
                                </x-forms.select>
                                <x-common.icon
                                    name="fas fa-sort"
                                    class="is-small is-left"
                                />
                                <x-common.validation-error property="is_active" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                    <div class="column is-12">
                        <x-forms.field>
                            <x-forms.label for="additional_info">
                                Note <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.control>
                                <x-forms.textarea
                                    name="additional_info"
                                    id="additional_info"
                                    rows="5"
                                    class="summernote textarea"
                                    placeholder="Description or note to be taken"
                                >{{  $account->additional_info ?? old('additional_info') }}
                            </x-forms.textarea>
                            <x-common.validation-error property="additional_info" />
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </div>
            <x-content.footer>
                <x-common.save-button />
            </x-content.footer>
        </form>
    </x-common.content-wrapper>
@endsection
