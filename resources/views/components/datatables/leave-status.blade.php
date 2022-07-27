 @if ($leave->isCancelled())
     <span class="tag is-small bg-gold has-text-white">
         <x-common.icon name="fas fa-times-circle" />
         <span>
             Cancelled
         </span>
     </span>
 @elseif (!$leave->isApproved())
     <span class="tag is-small bg-purple has-text-white">
         <x-common.icon name="fas fa-clock" />
         <span> Waiting Approval </span>
     </span>
 @else
     <span class="tag is-small bg-gold has-text-white">
         <x-common.icon name="fas fa-check-circle" />
         <span> Approved </span>
     </span>
 @endif
