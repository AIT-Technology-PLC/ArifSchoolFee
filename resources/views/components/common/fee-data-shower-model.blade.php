@props(['estimated', 'collected'])

<div class="box bg-white text-softblue" style="border-left: 2px solid !important; height: 400px; display: flex; justify-content: center; align-items: center;">
    <div class="columns is-marginless is-vcentered is-mobile is-multiline">
        <div class="column is-12 has-text-centered py-0">
            <div class="is-size-7 has-text-weight-bold is-uppercase">
                Estimation
            </div>
            <div class="is-size-4 has-text-weight-bold">
                {{ money($estimated) }}
            </div>
            <div class="my-5">
                <x-common.icon name="fas fa-sack-dollar" class="fa-5x my-5" />
            </div>
        </div>

        <div class="column is-12 has-text-centered py-0">
            <div class="columns is-marginless is-mobile">
                <div class="column is-6">
                    <div class="is-size-4 has-text-weight-bold">
                        {{ money($collected) }}
                    </div>
                    <div class="is-size-7 is-uppercase">
                        Collected
                    </div>
                </div>

                <div class="column is-6">
                    <div class="is-size-4 has-text-weight-bold">
                        {{ money($estimated - $collected) }}
                    </div>
                    <div class="is-size-7 is-uppercase">
                        Remaining
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
