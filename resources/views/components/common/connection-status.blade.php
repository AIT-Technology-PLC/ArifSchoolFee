<span
    x-data="connectionStatus"
    x-init="isOffline"
    @online.window="openOnlineBox"
    @offline.window="openOfflineBox"
>
    <div
        style="position: fixed; bottom: 0;left: 0;right: 0;"
        class="bg-green has-text-white has-text-centered is-hidden"
        x-ref="online"
    >
        <span class="icon">
            <i class="fas fa-globe"></i>
        </span>
        <span>
            Back Online
        </span>
    </div>
    <div
        style="position: fixed; bottom: 0;left: 0;right: 0;"
        class="has-background-grey-dark has-text-white has-text-centered is-hidden"
        x-ref="offline"
    >
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            You're Offline
        </span>
    </div>
</span>
