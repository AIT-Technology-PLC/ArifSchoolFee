<span class="tag is-small {{ empty($content) || $content > now() ? 'btn-green' : 'btn-purple' }} is-outlined has-text-weight-medium">
    {{ $content?->toFormattedDateString() ?? 'N/A' }}
</span>
