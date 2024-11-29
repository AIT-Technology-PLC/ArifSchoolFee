@if ($message->type == 'sms')
    <span class="tag bg-lightgreen text-gold has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-comment-sms"></i>
        </span>
        <span>
            SMS
        </span>
    </span>
@elseif($message->type == 'email')
    <span class="tag bg-lightgreen text-gold has-text-weight-medium">
            <span class="icon">
                <i class="fas fa-envelope"></i>
            </span>
            <span>
                Email
            </span>
    </span>
@else
    <span class="tag bg-lightpurple text-gold has-text-weight-medium">
        <span class="icon">
            <i class="fas fa-comment"></i>
        </span>
        <span>
            Email & SMS
        </span>
    </span>
@endif
