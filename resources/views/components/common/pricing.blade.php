<div class="columns is-marginless is-multiline mt-3 mb-0">
    <div class="column is-offset-9 is-3">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <tbody>
                    <tr>
                        <td class="text-green has-text-weight-medium">Sub-Total</td>
                        <td
                            class="has-text-right"
                            x-text="moneyFormat(Pricing.subTotal({{ $data }}))"
                        ></td>
                    </tr>
                    <tr>
                        <td class="text-green has-text-weight-medium">Tax</td>
                        <td
                            class="has-text-right"
                            x-text="moneyFormat(Pricing.grandTotal({{ $data }})-Pricing.subTotal({{ $data }}))"
                        ></td>
                    </tr>
                    <tr x-show="$store.hasWithholding">
                        <td class="text-green has-text-weight-medium">
                            Withholding Tax ({{ userCompany()->withholdingTaxes['tax_rate'] * 100 }}%)
                        </td>
                        <td
                            class="has-text-right"
                            x-text="moneyFormat(Pricing.withheldAmount({{ $data }}))"
                        ></td>
                    </tr>
                    <tr>
                        <td class="text-green has-text-weight-medium">Grand Total</td>
                        <td
                            class="has-text-weight-bold is-underlined has-text-right"
                            x-text="moneyFormat(Pricing.grandTotal({{ $data }}))"
                        ></td>
                    </tr>
                    <tr x-show="$store.hasWithholding">
                        <td class="text-green has-text-weight-medium"></td>
                        <td
                            class="has-text-weight-bold is-underlined has-text-right"
                            x-text="moneyFormat(Pricing.grandTotal({{ $data }})-Pricing.withheldAmount({{ $data }}))"
                        ></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
