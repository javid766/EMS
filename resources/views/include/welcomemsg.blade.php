<?php 
if(session()->get('isFirstLogin'))
{?> 
<div class="modal fade" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Welcome')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <span>Welocome to ZETA EMS</span>
            </div>
         
        </div>
    </div>
</div>
<script type="text/javascript">
    $('#welcomeModal').modal('show');
</script>
<?php 
session(['isFirstLogin' => 0]);
} ?>

    