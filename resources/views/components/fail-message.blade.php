@props(['message', 'margin-bottom' => 'mb-6'])

@if (!$message)
    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
        <p class="has-text-grey text-purple is-size-6">
            <span class="icon">
                <i class="fas fa-exclamation-circle"></i>
            </span>
            <span>
                This Adjustment has not been approved.
            </span>
        </p>
    </div>
@endif
