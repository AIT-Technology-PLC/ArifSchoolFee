<div
    x-data="{ 
        isHidden: true, 
        modalId: null,
        fee_discount_id: '{{ old('fee_discount_id', $assignFeeMaster->fee_discount_id) }}',
        discount_amount: parseFloat('{{ old('discount_amount', $assignFeeMaster->discount_amount ?? 0) }}'),
        amount: parseFloat('{{ old('amount', $assignFeeMaster->feeMaster->amount) }}'),
        fine_amount: parseFloat('{{ old('fine_amount', $assignFeeMaster->getFineAmount()) }}'),
        exchange_rate: '{{ old('exchange_rate', $assignFeeMaster->getExchangeRate() ?? null) }}',
        commission_amount: 0,
        company_id: '{{ $assignFeeMaster->company->id }}',
        is_commission_from_payer: false,

        calculateTotal() {
                const total = this.amount + this.fine_amount - (this.discount_amount || 0);
                this.updateCommission(total);

                const calculatedValue = total;

                if (this.is_commission_from_payer) {
                    calculatedValue += this.commission_amount;
                }

                return (calculatedValue * (this.exchange_rate || 1)).toFixed(2); 
        },
        
        async updateCommission(totalAmount) {
            try {
                const response = await fetch('/calculate-commission', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        amount: totalAmount,
                        company_id: this.company_id,
                    }),
                });

                const data = await response.json();

                if (response.ok) {
                    this.commission_amount = parseFloat(data.commission || 0);
                    this.is_commission_from_payer = data.is_commission_from_payer || false;
                } else {
                    console.error('Error calculating commission:', data.message);
                }
            } catch (error) {
                console.error('Error calculating commission:', error);
            }
        },
    }"
    @open-fee-details-modal.window="if ($event.detail.id === '{{ $assignFeeMaster->id }}') { modalId = $event.detail.id; isHidden = false }"
    x-show="modalId === '{{ $assignFeeMaster->id }}'"
    :class="{'is-active': !isHidden}" 
    class="modal"
    x-cloak
    x-transition
>
<div class="modal-background" @click="isHidden = true"></div>
    <div class="modal-content p-lr-20">
        <x-common.content-wrapper>
            <x-content.header >
                <x-slot name="header">
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
                        Collect Assigned Fee
                    </h1>
                </x-slot>
                <button class="delete" aria-label="close"  @click="toggle"></button>
            </x-content.header>
            <form
                id="student_fee_detail"
                action="{{ route('payment.process', $assignFeeMaster->id) }}"
                method="POST"
                enctype="multipart/form-data"
                novalidate
            >
                @csrf
                <x-content.main>
                    <div class="columns is-marginless is-multiline">
                        <div class="column is-6">
                            <x-forms.label for="invoice_number">
                                Invoice Number <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="invoice_number"
                                        name="invoice_number"
                                        type="text"
                                        disabled
                                        value="{{ $assignFeeMaster->invoice_number }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="invoice_number" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="payment_date">
                                Date <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="payment_date"
                                        name="payment_date"
                                        type="date"
                                        placeholder="mm/dd/yyyy"
                                        value="{{ old('payment_date') ?? now()->toDateString() }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-calendar-alt"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="payment_date" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="amount">
                                Amount <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="text"
                                        id="amount"
                                        name="amount"
                                        placeholder="Amount"
                                        x-model="amount"
                                        readonly
                                    />
                                    <x-common.icon
                                        name="fas fa-money-bill"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="amount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="fine_amount">
                                Fine <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="number"
                                        name="fine_amount"
                                        id="fine_amount"
                                        placeholder="Fine"
                                        x-model="fine_amount"
                                        readonly
                                    />
                                    <x-common.icon
                                        name="fas fa-money-bill"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="fine_amount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.field>
                                <x-forms.label for="fee_discount_id">
                                    Discount Group <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left select is-fullwidth">
                                    <x-common.fee-discount-list :selected-id="$assignFeeMaster->student->id"/>
                                    <x-common.icon
                                        name="fas fa-layer-group"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="fee_discount_id" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="discount_amount">
                                Discount <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="number"
                                        name="discount_amount"
                                        id="discount_amount"
                                        placeholder="Discount"
                                        x-model="discount_amount"
                                        x-bind:value="fee_discount_id ? discount_amount : 0"
                                        @input="calculateTotal()" 
                                        readonly
                                    />
                                    <x-common.icon
                                        name="fas fa-percent"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="discount_amount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-12">
                            <x-forms.field>
                                <x-forms.label for="payment_mode">
                                    Payment Method <sup class="has-text-danger">*</sup>
                                </x-forms.label>
                                <x-forms.control class="has-icons-left select is-fullwidth">
                                    <x-common.payment-method-list />
                                    <x-common.icon
                                        name="fas fa-credit-card"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="payment_mode" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label for="reference_number">
                                Reference No <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="text"
                                        name="reference_number"
                                        id="reference_number"
                                        placeholder="Reference Number"
                                        value="{{ old('reference_number') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-hashtag"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="reference_number" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        @if ($assignFeeMaster->getExchangeRate() !== null)                            
                            <div class="column is-6">
                                <x-forms.label for="exchange_rate">
                                    Exchange Rate <sup class="has-text-danger"></sup>
                                </x-forms.label>
                                <x-forms.field>
                                    <x-forms.control class="has-icons-left">
                                        <x-forms.input
                                            type="text"
                                            name="exchange_rate"
                                            id="exchange_rate"
                                            placeholder="Exchange Rate"
                                            x-model="exchange_rate"
                                            readonly
                                        />
                                        <x-common.icon
                                            name="fas fa-exchange-alt"
                                            class="is-large is-left"
                                        />
                                        <x-common.validation-error property="exchange_rate" />
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                        <div class="column is-6">
                            <x-forms.label for="commission_amount">
                                Commission <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        type="text"
                                        name="commission_amount"
                                        id="commission_amount"
                                        placeholder="Commission"
                                        x-model="commission_amount"
                                        readonly
                                    />
                                    <x-common.icon
                                        name="fas fa-money-bill"
                                        class="is-large is-left"
                                    />
                                    <x-common.validation-error property="commission_amount" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-6">
                            <x-forms.label>
                                Total Price <sup class="has-text-weight-light"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        x-bind:value="calculateTotal()"
                                        type="number"
                                        readonly
                                    />
                                    <x-common.icon
                                        name="fas fa-money-check"
                                        class="is-small is-left"
                                    />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </x-content.main>
                <x-content.footer>
                    <div class="field">
                        <div class="control">
                            <button
                                id="submitButton"
                                type="submit"
                                class="button has-text-white bg-softblue is-fullwidth is-uppercase is-size-6 has-text-weight-semibold py-3 px-3"
                            >
                                <span class="icon">
                                    <i class="fas fa-spinner"></i>
                                </span>
                                <span>
                                    Process Payment
                                </span>
                            </button>
                        </div>
                    </div>
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
</div>
