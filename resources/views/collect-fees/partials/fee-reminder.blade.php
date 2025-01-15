<div
    x-data="{ 
        isHidden: true, 
        modalId: null,
        amount: parseFloat('{{ $assignFeeMaster->feeMaster->amount }}'),
        fine_amount: parseFloat('{{ $assignFeeMaster->getFineAmount() }}'),
        commission_amount: 0,
        company_id: '{{ $assignFeeMaster->company->id }}',
        is_commission_from_payer: false,

        calculateTotal() {
                const total = this.amount + this.fine_amount;
                this.updateCommission(total);

                if (this.is_commission_from_payer) {
                    return (total + this.commission_amount).toFixed(2);
                }
                    
                return total.toFixed(2); 
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
    @open-fee-reminder-modal.window="if ($event.detail.id === '{{ $assignFeeMaster->id }}') { modalId = $event.detail.id; isHidden = false }"
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
                        Send Payment Code
                    </h1>
                </x-slot>
                <button class="delete" aria-label="close"  @click="toggle"></button>
            </x-content.header>
            <form
                id="student_fee_detail"
                action="{{ route('fee-reminder', $assignFeeMaster->id) }}"
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
                            <x-forms.label for="phone">
                                Send To <sup class="has-text-danger">*</sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        placeholder="Phone Number"
                                        readonly
                                        value="{{ $assignFeeMaster->student->phone }}"
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
                            <x-forms.label for="other_phone">
                                Other Phone No <sup class="has-text-danger"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left">
                                    <x-forms.input
                                        class="is-fullwidth"
                                        id="other_phone"
                                        name="other_phone"
                                        type="tel"
                                        placeholder="Other Phone Number"
                                        value="{{ old('other_phone') }}"
                                    />
                                    <x-common.icon
                                        name="fas fa-phone"
                                        class="is-small is-left"
                                    />
                                    <x-common.validation-error property="other_phone" />
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                        <div class="column is-12">
                            <x-forms.label>
                                Total Price <sup class="has-text-weight-light"></sup>
                            </x-forms.label>
                            <x-forms.field>
                                <x-forms.control class="has-icons-left is-expanded">
                                    <x-forms.input
                                        x-bind:value="calculateTotal()"
                                        type="number"
                                        readonly
                                        disabled
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
                                    <i class="fas fa-bell"></i>
                                </span>
                                <span>
                                    Send Payment Code
                                </span>
                            </button>
                        </div>
                    </div>
                </x-content.footer>
            </form>
        </x-common.content-wrapper>
    </div>
</div>
