@if (session()->has('deleted'))
    <div class="message is-success">
        <p class="message-body">
            <span class="icon">
                <i class="fas fa-check-circle"></i>
            </span>
            <span>
                {{ $model }} was deleted successfully
            </span>
        </p>
    </div>
@endif
