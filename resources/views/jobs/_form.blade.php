<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-1"><i class="fa fa-briefcase me-2"></i>{{ isset($job) ? 'Edit Job Posting' : 'Post a New Job' }}</h5>
                <p class="text-muted small mb-4">{{ isset($job) ? 'Update' : 'Fill in' }} the details below to {{ isset($job) ? 'update your' : 'create a new' }} job posting</p>

                <form method="POST" action="{{ isset($job) ? route('jobs.update', $job) : route('jobs.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if(isset($job)) @method('PUT') @endif

                    {{-- Title --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Job Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $job->title ?? '') }}"
                               placeholder="e.g. Senior Laravel Developer" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Category + Type --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $job->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Job Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="fixed"     {{ old('type', $job->type ?? '') == 'fixed'     ? 'selected' : '' }}>Fixed Price</option>
                                <option value="hourly"    {{ old('type', $job->type ?? '') == 'hourly'    ? 'selected' : '' }}>Hourly Rate</option>
                                <option value="milestone" {{ old('type', $job->type ?? '') == 'milestone' ? 'selected' : '' }}>Milestone Based</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="8" required
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe the project, requirements, and what you expect from freelancers...">{{ old('description', $job->description ?? '') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Budget + Deadline --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Budget Min (Nu.)</label>
                            <input type="number" name="budget_min" min="1" class="form-control"
                                   value="{{ old('budget_min', $job->budget_min ?? '') }}"
                                   placeholder="e.g. 10000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Budget Max (Nu.)</label>
                            <input type="number" name="budget_max" min="1" class="form-control"
                                   value="{{ old('budget_max', $job->budget_max ?? '') }}"
                                   placeholder="e.g. 50000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Deadline</label>
                            <input type="date" name="deadline" min="{{ date('Y-m-d') }}" class="form-control"
                                   value="{{ old('deadline', optional($job->deadline ?? null)->format('Y-m-d')) }}">
                        </div>
                    </div>

                    {{-- Location + Experience --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Location (Dzongkhag)</label>
                            <select name="dzongkhag" class="form-select">
                                <option value="">Remote / Any</option>
                                @foreach(\App\Models\Profile::DZONGKHAGS as $dz)
                                <option value="{{ $dz }}" {{ old('dzongkhag', $job->dzongkhag ?? '') == $dz ? 'selected' : '' }}>{{ $dz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Experience Level</label>
                            <select name="experience_level" class="form-select">
                                <option value="">Any Level</option>
                                <option value="entry"        {{ old('experience_level', $job->experience_level ?? '') == 'entry'        ? 'selected' : '' }}>Entry Level</option>
                                <option value="intermediate" {{ old('experience_level', $job->experience_level ?? '') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="expert"       {{ old('experience_level', $job->experience_level ?? '') == 'expert'       ? 'selected' : '' }}>Expert</option>
                            </select>
                        </div>
                    </div>

                    {{-- Skills --}}
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Required Skills <span class="text-muted">(Select all that apply)</span></label>
                        <div class="border rounded p-3" style="background: #f8f9fa;">
                            <input type="text" id="skillSearch" placeholder="Search skills..." 
                                   class="form-control form-control-sm mb-2">
                            <div class="row g-2" id="skillsGrid" style="max-height: 300px; overflow-y: auto;">
                                @foreach($skills as $skill)
                                <div class="col-6 col-md-4 col-lg-3 skill-item" data-skill-name="{{ strtolower($skill->name) }}">
                                    <div class="form-check">
                                        <input type="checkbox" name="skills[]" value="{{ $skill->id }}"
                                               id="skill_{{ $skill->id }}"
                                               class="form-check-input"
                                               {{ in_array($skill->id, old('skills', isset($job) ? $job->skills->pluck('id')->toArray() : [])) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="skill_{{ $skill->id }}">
                                            {{ $skill->name }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div id="noSkillsFound" class="text-center py-3 text-muted small d-none">
                                No skills found matching your search
                            </div>
                        </div>
                        <small class="form-text text-muted">Select skills that are required or preferred for this job</small>
                    </div>

                    {{-- Attachments --}}
                    <div class="mb-4">
                        <label class="form-label small fw-semibold">Attachments <span class="text-muted">(Optional)</span></label>
                        <input type="file" name="attachments[]" multiple accept=".pdf,.doc,.docx,.jpg,.png"
                               class="form-control">
                        <small class="form-text text-muted">Max 10 MB per file. Accepted: PDF, Word, images.</small>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 pt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            {{ isset($job) ? 'Update Job' : 'Post Job' }}
                        </button>
                        <a href="{{ route('jobs.my') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('skillSearch');
    const skillsGrid = document.getElementById('skillsGrid');
    const skillItems = document.querySelectorAll('.skill-item');
    const noSkillsFound = document.getElementById('noSkillsFound');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;

            skillItems.forEach(function(item) {
                const skillName = item.getAttribute('data-skill-name');
                if (skillName.includes(searchTerm)) {
                    item.style.display = 'block';
                    visibleCount++;
                } else {
                    item.style.display = 'none';
                }
            });

            if (visibleCount === 0) {
                skillsGrid.classList.add('d-none');
                noSkillsFound.classList.remove('d-none');
            } else {
                skillsGrid.classList.remove('d-none');
                noSkillsFound.classList.add('d-none');
            }
        });
    }
});
</script>
@endpush
