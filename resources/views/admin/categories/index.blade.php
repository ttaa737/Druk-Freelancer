@extends('layouts.admin')
@section('title', 'Categories & Skills')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Categories & Skills</h4>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal"><i class="fa fa-plus me-1"></i>Add Category</button>
</div>

<div class="accordion" id="catAccordion">
    @foreach($categories as $category)
    <div class="accordion-item mb-2">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#cat{{ $category->id }}">
                {{ $category->name }} <span class="badge bg-secondary ms-2">{{ $category->skills->count() }} skills</span>
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
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header"><h6 class="modal-title fw-bold">Edit Category</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf @method('PUT')
                        <div class="mb-2"><input type="text" name="name" class="form-control" value="{{ $category->name }}" required></div>
                        <div class="mb-2"><input type="text" name="icon" class="form-control" value="{{ $category->icon }}" placeholder="Icon class (fa fa-...)"></div>
                        <button class="btn btn-primary btn-sm w-100">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"><h6 class="modal-title fw-bold">Add Category</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-2"><input type="text" name="name" class="form-control" placeholder="Category name" required></div>
                    <div class="mb-2"><input type="text" name="icon" class="form-control" placeholder="Icon class (fa fa-...)"></div>
                    <button class="btn btn-primary btn-sm w-100">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
