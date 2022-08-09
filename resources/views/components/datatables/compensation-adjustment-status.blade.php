 @if ($compensationAdjustment->isCancelled())
     <span class="tag is-small bg-gold has-text-white">
         <x-common.icon name="fas fa-times-circle" />
         <span>
             Cancelled
         </span>
     </span>
 @elseif (!$compensationAdjustment->isApproved())
     <span class="tag is-small bg-purple has-text-white">
         <x-common.icon name="fas fa-clock" />
         <span> Waiting Approval </span>
     </span>
 @else
     <span class="tag is-small bg-gold has-text-white">
         <x-common.icon name="fas fa-exclamation-circle" />
         <span> Approved </span>
     </span>
 @endif
