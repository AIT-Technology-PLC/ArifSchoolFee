<x-content.main x-data="customerForm({{ Js::from($customer) }})">
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
                        x-on:change="fetchByCompanyName"
                        x-model="customer.company_name"
                    />
                    <x-common.icon
                        name="fas fa-building"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="company_name" />
                </x-forms.control>
            </x-forms.field>
        </div>
        <div class="column is-6">
            <x-forms.field>
                <x-forms.label for="credit_amount_limit">
                    Credit Limit <sup class="has-text-danger">*</sup>
                </x-forms.label>
                <x-forms.control class="has-icons-left">
                    <x-forms.input
                        id="credit_amount_limit"
                        name="credit_amount_limit"
                        type="number"
                        placeholder="Credit Limit"
                        x-model="customer.credit_amount_limit"
                    />
                    <x-common.icon
                        name="fas fa-dollar-sign"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="credit_amount_limit" />
                </x-forms.control>
            </x-forms.field>
        </div>
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
                        x-on:change="fetchByTin"
                        x-model="customer.tin"
                    />
                    <x-common.icon
                        name="fas fa-hashtag"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="tin" />
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
                        x-model="customer.address"
                    />
                    <x-common.icon
                        name="fas fa-map-marker-alt"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="address" />
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
                        x-model="customer.contact_name"
                    />
                    <x-common.icon
                        name="fas fa-address-book"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="contact_name" />
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
                        x-model="customer.email"
                    />
                    <x-common.icon
                        name="fas fa-at"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="email" />
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
                        x-model="customer.phone"
                    />
                    <x-common.icon
                        name="fas fa-phone"
                        class="is-small is-left"
                    />
                    <x-common.validation-error property="phone" />
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
                        x-model="customer.country"
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
                    <x-common.validation-error property="country" />
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
            Alpine.data("customerForm", (customer) => ({
                customer: {
                    credit_amount_limit: 0.00,
                },

                async init() {
                    await Customer.init();

                    if (Object.keys(customer).length) {
                        this.customer = customer;
                    }
                },
                fetchByCompanyName() {
                    let customer = Customer.whereCompanyName(this.customer.company_name) || {};

                    if (Object.keys(customer).length) {
                        this.changeProperties(customer);
                    }
                },
                fetchByTin() {
                    let customer = Customer.whereTin(this.customer.tin) || {};

                    if (Object.keys(customer).length) {
                        this.changeProperties(customer);
                    }
                },
                changeProperties(customer) {
                    this.customer.company_name = customer.company_name || '';
                    this.customer.credit_amount_limit = customer.credit_amount_limit || 0.00;
                    this.customer.tin = customer.tin || '';
                    this.customer.address = customer.address || '';
                    this.customer.contact_name = customer.contact_name || '';
                    this.customer.email = customer.email || '';
                    this.customer.phone = customer.phone || '';
                    this.customer.country = customer.country || '';
                }
            }));
        });
    </script>
@endpush
