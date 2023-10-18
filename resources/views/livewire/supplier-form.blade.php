<form
    id="formOne"
    method="POST"
    enctype="multipart/form-data"
    novalidate
    wire:submit="{{ $method }}"
>
    @csrf

    @if ($method == 'update')
        @method('PATCH')
    @endif

    <x-content.main>
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="company_name">
                        Company Name <sup class="has-text-danger">*</sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="company_name"
                            name="company_name"
                            type="text"
                            placeholder="Company Name"
                            wire:focusout="fetchByCompanyName"
                            wire:model="supplier.company_name"
                        />
                        <x-common.icon
                            name="fas fa-building"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.company_name" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            @if (isFeatureEnabled('Debt Management'))
                <div class="column is-6">
                    <x-forms.field>
                        <x-forms.label for="debt_amount_limit">
                            Debt Limit <sup class="has-text-danger">*</sup>
                        </x-forms.label>
                        <x-forms.control class="has-icons-left">
                            <x-forms.input
                                id="debt_amount_limit"
                                name="debt_amount_limit"
                                type="number"
                                placeholder="Debt Limit"
                                wire:model="supplier.debt_amount_limit"
                            />
                            <x-common.icon
                                name="fas fa-dollar-sign"
                                class="is-small is-left"
                            />
                            <x-common.validation-error property="supplier.debt_amount_limit" />
                        </x-forms.control>
                    </x-forms.field>
                </div>
            @endif
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="tin">
                        TIN <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="tin"
                            name="tin"
                            type="number"
                            placeholder="Tin No"
                            wire:focusout="fetchByTin"
                            wire:model="supplier.tin"
                        />
                        <x-common.icon
                            name="fas fa-hashtag"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.tin" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="address">
                        Address <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="address"
                            name="address"
                            type="text"
                            placeholder="Address"
                            wire:model="supplier.address"
                        />
                        <x-common.icon
                            name="fas fa-map-marker-alt"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.address" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="contact_name">
                        Contact Name <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="contact_name"
                            name="contact_name"
                            type="text"
                            placeholder="Contact Name"
                            wire:model="supplier.contact_name"
                        />
                        <x-common.icon
                            name="fas fa-address-book"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.contact_name" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="email">
                        Email <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="email"
                            name="email"
                            type="text"
                            placeholder="Email Address"
                            wire:model="supplier.email"
                        />
                        <x-common.icon
                            name="fas fa-at"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.email" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="phone">
                        Phone <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            id="phone"
                            name="phone"
                            type="text"
                            placeholder="Phone/Telephone"
                            wire:model="supplier.phone"
                        />
                        <x-common.icon
                            name="fas fa-phone"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.phone" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="country">
                        Country <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.select
                            class="is-fullwidth"
                            id="country"
                            name="country"
                            wire:model="supplier.country"
                        >
                            <option
                                selected
                                disabled
                            > Select Country/City </option>
                            <optgroup label="Ethiopian Cities">
                                @include('lists.cities')
                            </optgroup>
                            <optgroup label="Others">
                                @include('lists.countries')
                            </optgroup>
                            <option value="">None</option>
                        </x-forms.select>
                        <x-common.icon
                            name="fas fa-globe"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.country" />
                    </x-forms.control>
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="business_license_attachment">
                        Business License <sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <div class="file has-name">
                        <label class="file-label">
                            <x-forms.input
                                class="file-input"
                                type="file"
                                id="business_license_attachment"
                                name="business_license_attachment"
                                wire:model="supplier.business_license_attachment"
                            />
                            <span class="file-cta bg-green has-text-white">
                                <x-common.icon
                                    name="fas fa-upload"
                                    class="file-icon"
                                />
                                <span class="file-label">
                                    Upload Business License
                                </span>
                            </span>
                            <span class="file-name">
                                @empty($supplier['business_license_attachment'])
                                    Select File
                                @else
                                    {{ $supplier['business_license_attachment'] }}
                                @endempty
                            </span>
                        </label>
                    </div>
                    <x-common.validation-error property="supplier.business_license_attachment" />
                </x-forms.field>
            </div>
            <div class="column is-6">
                <x-forms.field>
                    <x-forms.label for="business_license_expires_on">
                        Business License Expire On<sup class="has-text-danger"></sup>
                    </x-forms.label>
                    <x-forms.control class="has-icons-left">
                        <x-forms.input
                            type="date"
                            name="business_license_expires_on"
                            id="business_license_expires_on"
                            placeholder="mm/dd/yyyy"
                            wire:model="supplier.business_license_expires_on"
                        />
                        <x-common.icon
                            name="fas fa-calendar-alt"
                            class="is-small is-left"
                        />
                        <x-common.validation-error property="supplier.business_license_expires_on" />
                    </x-forms.control>
                </x-forms.field>
            </div>
        </div>
    </x-content.main>

    <x-content.footer>
        <x-common.save-button />
    </x-content.footer>
</form>
