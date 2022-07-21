 @if (!$advancement->isApproved())
     <span class="tag is-small bg-purple has-text-white">
         <x-common.icon name="fas fa-clock" />
         <span> Waiting Approval</span>
     </span>
 @else
     <span class="tag is-small bg-green has-text-white">
         <x-common.icon name="fas fa-check-circle" />
         <span> Approved </span>
     </span>
 @endif
