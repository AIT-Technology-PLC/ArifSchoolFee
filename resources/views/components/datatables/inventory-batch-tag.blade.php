<span class="tag is-small {{ empty($content) || $content > now() ? 'bg-lightgreen text-green' : 'bg-lightpurple text-purple' }}">
    {{ $content?->toFormattedDateString() ?? 'N/A' }}
</span>
