<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __('Confirm Delete')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <span>Are you sure you want to delete this record?</span>
            </div>
            <div class="modal-footer modal-footer-border">
                <button type="button" class="btn btn-warning" data-dismiss="modal">{{ __('Cancel')}}</button>
                <a href="#" id="deleteRecord" class="btn btn-danger">{{ __('Delete')}}</a>
            </div>
        </div>
    </div>
</div>