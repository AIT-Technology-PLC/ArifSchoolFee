<div class="columns is-marginless is-multiline mt-5 mb-0">
    <div class="column is-6">
        <x-forms.label>
            Sub-Total <sup class="has-text-danger"></sup>
        </x-forms.label>
        <x-forms.field>
            <x-forms.control class="has-icons-left is-expanded">
                <x-forms.input
                    x-bind:value="Pricing.subTotal({{ $data }}).toFixed(2)"
                    type="number"
                    readonly
                    disabled
                />
                <x-common.icon
                    name="fas fa-file-invoice-dollar"
                    class="is-small is-left"
                />
            </x-forms.control>
        </x-forms.field>
    </div>
    <div class="column is-6">
        <x-forms.label>
            Grand Total <sup class="has-text-danger"></sup>
        </x-forms.label>
        <x-forms.field>
            <x-forms.control class="has-icons-left is-expanded">
                <x-forms.input
                    x-bind:value="Pricing.grandTotal({{ $data }}).toFixed(2)"
                    type="number"
                    readonly
                    disabled
                />
                <x-common.icon
                    name="fas fa-file-invoice-dollar"
                    class="is-small is-left"
                />
            </x-forms.control>
        </x-forms.field>
    </div>
</div>
