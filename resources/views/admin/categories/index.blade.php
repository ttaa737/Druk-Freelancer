@extends('layouts.admin')
@section('title', 'Categories & Skills')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Categories & Skills</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa fa-plus me-1"></i>Add Category</button>
</div>

<div class="accordion" id="catAccordion">
    @foreach($categories as $category)
    <div class="accordion-item mb-2">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cat{{ $category->id }}">
                @if($category->icon)<i class="{{ $category->icon }} me-2"></i>@endif{{ $category->name }} <span class="badge bg-secondary ms-2">{{ $category->skills->count() }} skills</span>
            </button>
        </h2>
        <div id="cat{{ $category->id }}" class="accordion-collapse collapse">
            <div class="accordion-body">
                <!-- Skills Table -->
                <div class="table-responsive mb-3">
                    <table class="table table-sm mb-0">
                        <thead class="table-light"><tr><th>Skill</th><th class="text-end">Action</th></tr></thead>
                        <tbody>
                            @forelse($category->skills as $skill)
                            <tr>
                                <td class="small">{{ $skill->name }}</td>
                                <td class="text-end">
                                    <form method="POST" action="{{ route('admin.categories.skills.destroy', [$category, $skill]) }}">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete skill?')"><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-muted small text-center">No skills yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Add Skill -->
                <form method="POST" action="{{ route('admin.categories.skills.store', $category) }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="New skill name" required>
                    <button class="btn btn-sm btn-success">Add Skill</button>
                </form>
                <!-- Edit/Delete Category -->
                <hr>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editCat{{ $category->id }}">Edit Category</button>
                    <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete category and all skills?')">Delete Category</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCat{{ $category->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-secondary bg-opacity-10">
                    <h5 class="modal-title fw-bold"><i class="fa fa-edit me-2 text-secondary"></i>Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $category->name }}" placeholder="e.g., Web Development" required maxlength="100">
                            <small class="text-muted">Max 100 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" placeholder="Brief description of this category..." maxlength="500" rows="3">{{ $category->description ?? '' }}</textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-muted">Max 500 characters</small>
                                <small class="text-muted"><span id="descCharsEdit{{ $category->id }}">0</span>/500</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Icon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i id="iconPreviewEdit{{ $category->id }}" class="fa {{ $category->icon ?? 'fa-folder' }}" style="font-size: 18px;"></i></span>
                                <input type="text" name="icon" id="iconInputEdit{{ $category->id }}" class="form-control" value="{{ $category->icon ?? '' }}" placeholder="e.g., fa-code, fa-briefcase" maxlength="100">
                            </div>
                            <small class="text-muted d-block mt-1"><a href="https://fontawesome.com/icons" target="_blank">Browse Font Awesome Icons</a> - Use format: fa-icon-name</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    // Icon preview for each category edit modal
    document.getElementById('iconInputEdit{{ $category->id }}')?.addEventListener('input', function(e) {
        const icon = e.target.value || 'fa-folder';
        document.getElementById('iconPreviewEdit{{ $category->id }}').className = 'fa ' + icon;
    });
    // Character counter for description
    document.querySelector('#editCat{{ $category->id }} textarea[name="description"]')?.addEventListener('input', function(e) {
        document.getElementById('descCharsEdit{{ $category->id }}').textContent = e.target.value.length;
    });
    // Initialize character count on modal open
    document.getElementById('editCat{{ $category->id }}')?.addEventListener('show.bs.modal', function(e) {
        document.getElementById('descCharsEdit{{ $category->id }}').textContent = document.querySelector('#editCat{{ $category->id }} textarea[name="description"]')?.value?.length || 0;
    });
    </script>
    @endforeach
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary bg-opacity-10">
                <h5 class="modal-title fw-bold"><i class="fa fa-plus me-2 text-primary"></i>Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.categories.store') }}" id="addCategoryForm">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info small mb-3">
                        <i class="fa fa-info-circle me-2"></i>
                        Create new job categories to help organize freelance work and skills.
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="categoryNameInput" class="form-control" placeholder="e.g., Web Development" required maxlength="100">
                        <small class="text-muted">Max 100 characters</small>
                        @error('name')
                            <div class="alert alert-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" id="descriptionInput" class="form-control" placeholder="Brief description of this category..." maxlength="500" rows="3"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Max 500 characters</small>
                            <small class="text-muted"><span id="descChars">0</span>/500</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon (Optional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i id="iconPreview" class="fa fa-folder" style="font-size: 18px;"></i>
                            </span>
                            <input type="text" name="icon" id="iconInput" class="form-control" placeholder="e.g., fa-code, fa-briefcase" maxlength="100">
                        </div>
                        <small class="text-muted d-block mt-1"><a href="https://fontawesome.com/icons" target="_blank">Browse Font Awesome Icons</a> - Use format: fa-icon-name</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add Category Modal - Icon Preview
document.getElementById('iconInput')?.addEventListener('input', function(e) {
    const icon = e.target.value || 'fa-folder';
    try {
        document.getElementById('iconPreview').className = 'fa ' + icon;
    } catch(err) {
        document.getElementById('iconPreview').className = 'fa fa-folder';
    }
});

// Add Category Modal - Character Counter
document.getElementById('descriptionInput')?.addEventListener('input', function(e) {
    document.getElementById('descChars').textContent = e.target.value.length;
});

// Reset modal on close
document.getElementById('addCategoryModal')?.addEventListener('hide.bs.modal', function() {
    document.getElementById('addCategoryForm').reset();
    document.getElementById('iconPreview').className = 'fa fa-folder';
    document.getElementById('descChars').textContent = '0';
});

// Initialize modal on show
document.getElementById('addCategoryModal')?.addEventListener('show.bs.modal', function() {
    document.getElementById('categoryNameInput').focus();
});
</script>
@endsection
