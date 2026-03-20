<div class="modal fade" id="reasonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reasonModalLabel">Provide Reason</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-2"><small class="text-muted" id="reasonModalContext"></small></div>
        <div class="mb-3">
          <textarea class="form-control" id="reasonModalTextarea" rows="4" placeholder="Enter reason (required)" required></textarea>
          <small class="text-muted d-block mt-1">Provide a clear reason for this action. Maximum 500 characters.</small>
        </div>
        <div class="text-danger small d-none" id="reasonModalError" role="alert">
          <i class="fa fa-exclamation-circle me-1"></i>A reason is required.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="reasonModalConfirm">Confirm</button>
      </div>
    </div>
  </div>
</div>

<script>
  let __reasonModalTargetForm = null;
  
  function openReasonModal(form, contextText = ''){
    if (!form) {
      console.error('openReasonModal: form element not provided');
      return;
    }
    
    __reasonModalTargetForm = form;
    document.getElementById('reasonModalContext').textContent = contextText || '';
    document.getElementById('reasonModalTextarea').value = '';
    document.getElementById('reasonModalError').classList.add('d-none');
    
    try {
      const modal = new bootstrap.Modal(document.getElementById('reasonModal'));
      modal.show();
    } catch (err) {
      console.error('Error opening modal:', err);
      alert('Error opening modal. Please try again.');
    }
  }
  
  document.getElementById('reasonModalConfirm')?.addEventListener('click', function(){
    const ta = document.getElementById('reasonModalTextarea');
    const err = document.getElementById('reasonModalError');
    const v = ta?.value?.trim();
    
    if (!v) { 
      err.classList.remove('d-none'); 
      ta?.focus(); 
      return; 
    }
    
    if (!__reasonModalTargetForm) {
      console.error('No form target available');
      err.classList.remove('d-none');
      err.textContent = 'Error: Form not found. Please try again.';
      return;
    }
    
    const hidden = __reasonModalTargetForm.querySelector('input[name="reason"]');
    if (hidden) { 
      hidden.value = v;
      
      try {
        __reasonModalTargetForm.submit();
      } catch (err) {
        console.error('Error submitting form:', err);
        alert('Error submitting form. Please try again.');
      }
    } else {
      console.error('No hidden reason input found in form');
      alert('Error: Hidden field not found. Please refresh the page and try again.');
    }
  });
</script>
