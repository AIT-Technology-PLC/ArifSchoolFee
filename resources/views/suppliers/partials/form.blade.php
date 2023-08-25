<x-content.main x-data="supplierForm({{ Js::from($supplier) }})">
    <div class="columns is-marginless is-multiline">
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="company_name">
                    Company Name <sup class="has-text-danger">*</sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="text"
                        id="company_name"
                        name="company_name"
                        placeholder="Company Name"
                        x-on:change="fetchByCompanyName"
                        x-model="supplier.company_name"
                    />
                    <x-common.icon
                        name="fas fa-building"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="company_name" />
                </x-forms.control>
            </x-forms.field>
        </div>
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
                        x-model="supplier.debt_amount_limit"
                    />
                    <x-common.icon
                        name="fas fa-dollar-sign"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="debt_amount_limit" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="tin">
                    TIN <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="number"
                        id="tin"
                        name="tin"
                        placeholder="Tin No"
                        x-on:change="fetchByTin"
                        x-model="supplier.tin"
                    />
                    <x-common.icon
                        name="fas fa-hashtag"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="tin" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="address">
                    Address <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="text"
                        id="address"
                        name="address"
                        placeholder="Address"
                        x-model="supplier.address"
                    />
                    <x-common.icon
                        name="fas fa-map-marker-alt"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="address" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="contact_name">
                    Contact Name <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="text"
                        id="contact_name"
                        name="contact_name"
                        placeholder="Contact name"
                        x-model="supplier.contact_name"
                    />
                    <x-common.icon
                        name="fas fa-address-book"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="contact_name" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="email">
                    Email <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="text"
                        id="email"
                        name="email"
                        placeholder="Email Address"
                        x-model="supplier.email"
                    />
                    <x-common.icon
                        name="fas fa-at"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="email" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="phone">
                    Phone <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="Phone/Telephone"
                        x-model="supplier.phone"
                    />
                    <x-common.icon
                        name="fas fa-phone"
                        class="is-large is-left"
                    />
                    <x-common.validation-error property="phone" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="country">
                    Country <sup class="has-text-danger"> </sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left ">
                    <x-forms.select
                        class="is-fullwidth"
                        id="country"
                        name="country"
                        x-model="supplier.country"
                    >
                        <option
                            selected
                            disabled
                        > Select Country/City </option>
                        <optgroup label="Ethiopian Cities">
                            @include('lists.cities')
                        </optgroup>
                        <optgroup label="Countries">
                            @include('lists.countries')
                        </optgroup>
                        <option value="">None</option>
                    </x-forms.select>
                    <x-common.icon
                        name="fas fa-globe"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="country" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div
            class="column is-6"
            x-data="UploadedFileNameHandler"
        >
            <x-forms.field>
                <x-forms.label for="business_license_attachment">
                    Business License <sup class="has-text-danger"></sup>
                </x-forms.label>
                <div class="file has-name">
                    <label class="file-label">
                        <x-forms.input
                            class="file-input"
                            type="file"
                            name="business_license_attachment"
                            x-model="file"
                            x-on:change="getFileName"
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
                        <span
                            class="file-name"
                            x-text="fileName || supplier.business_license_attachment || 'Select File...'"
                        >
                        </span>
                    </label>
                </div>
                <x-common.validation-error property="business_license_attachment" />
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
                        id="business_license_expires_on"
                        name="business_license_expires_on"
                        placeholder="mm/dd/yyyy"
                        x-model="supplier.business_license_expires_on"
                    />
                    <x-common.icon
                        name="fas fa-calendar-alt"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="business_license_expires_on" />
                </x-forms.control>
            </x-forms.field>
        </div>
    </div>
</x-content.main>
<x-content.footer>
    <x-common.save-button />
</x-content.footer>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data("supplierForm", (supplier) => ({
                supplier: {
                    debt_amount_limit: supplier.debt_amount_limit || 0
                },

                async init() {
                    await Supplier.init();

                    if (Object.keys(supplier).length) {
                        this.supplier = supplier;
                    }
                },
                fetchByCompanyName() {
                    let supplier = Supplier.whereCompanyName(this.supplier.company_name) || {};

                    if (Object.keys(supplier).length) {
                        this.changeProperties(supplier);
                    }
                },
                fetchByTin() {
                    let supplier = Supplier.whereTin(this.supplier.tin) || {};

                    if (Object.keys(supplier).length) {
                        this.changeProperties(supplier);
                    }
                },
                changeProperties(supplier) {
                    this.supplier.company_name = supplier.company_name || '';
                    this.supplier.tin = supplier.tin || '';
                    this.supplier.address = supplier.address || '';
                    this.supplier.contact_name = supplier.contact_name || '';
                    this.supplier.email = supplier.email || '';
                    this.supplier.phone = supplier.phone || '';
                    this.supplier.country = supplier.country || '';
                    this.supplier.business_license_attachment = supplier.business_license_attachment || '';
                    this.supplier.business_license_expires_on = supplier.business_license_expires_on || '';
                }
            }));
        });
    </script>
@endpush
