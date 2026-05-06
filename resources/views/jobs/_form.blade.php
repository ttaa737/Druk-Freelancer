<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h5 class="card-title mb-1"><i class="fa fa-briefcase me-2"></i>{{ isset($job) ? 'Edit Job Posting' : 'Post a New Job' }}</h5>
                <p class="text-muted small mb-4">{{ isset($job) ? 'Update' : 'Fill in' }} the details below to {{ isset($job) ? 'update your' : 'create a new' }} job posting</p>

                @if(!isset($job) && auth()->check() && auth()->user()->verification_status !== 'verified')
                <div class="border border-warning rounded-3 bg-warning bg-opacity-10 p-3 mb-4">
                    <div class="d-flex align-items-start gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width:42px;height:42px;background:#fff3cd;color:#856404;">
                            <i class="fa fa-shield-alt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold mb-1">Verification needed before posting</div>
                            <div class="small text-muted mb-2">You can complete the form now, but your job will only be posted after your account is verified.</div>
                            <a href="{{ route('profile.edit') }}#tab-docs" class="btn btn-warning btn-sm">
                                <i class="fa fa-id-card me-1"></i> Go to Verification
                            </a>
                        </div>
                    </div>
                </div>
                @endif

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
                            <input type="number" name="budget_min" min="300" max="500000" class="form-control"
                                   value="{{ old('budget_min', $job->budget_min ?? '') }}"
                            placeholder="e.g. 300">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Budget Max (Nu.)</label>
                            <input type="number" name="budget_max" min="300" max="500000" class="form-control"
                                value="{{ old('budget_max', $job->budget_max ?? '') }}"
                                placeholder="e.g. 500000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Deadline</label>
                            {{-- Visible date picker (HTML5) for UX --}}
                            <input type="date" id="deadline_picker" class="form-control">
                            {{-- Hidden input submitted to server in dd/mm/YYYY format --}}
                            <input type="hidden" name="deadline" id="deadline" value="{{ old('deadline', optional($job->deadline ?? null)->format('d/m/Y')) }}">
                            <div id="deadlineError" class="invalid-feedback d-none"></div>
                            <small class="form-text text-muted">Select a deadline (format sent: dd/mm/yyyy)</small>
                            @error('deadline')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
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
                           <input type="file" name="attachments[]" id="jobAttachments" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               class="form-control" data-temp-upload-url="{{ route('jobs.attachments.temp') }}">
                           <small class="form-text text-muted d-block mt-1">Files are uploaded as drafts, so refreshing the page will not remove them.</small>
                           <div id="attachmentPreview" class="mt-2 d-flex flex-wrap gap-2"></div>
                           <div id="tempAttachmentsInputs"></div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex gap-2 pt-3">
                        @if(isset($job) || auth()->user()->verification_status === 'verified')
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-1"></i>
                            {{ isset($job) ? 'Update Job' : 'Post Job' }}
                        </button>
                        @else
                        <button type="button" class="btn btn-primary" disabled>
                            <i class="fa fa-save me-1"></i>
                            Post Job
                        </button>
                        @endif
                        <a href="{{ route('jobs.my') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                    @if(!isset($job) && auth()->check() && auth()->user()->verification_status !== 'verified')
                    <div class="alert alert-info mt-3 mb-0 py-2 px-3 small d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="fa fa-info-circle me-1"></i>
                            Verify your account to enable job posting.
                        </div>
                        <a href="{{ route('profile.edit') }}#tab-docs" class="fw-semibold text-decoration-none">Open Verification</a>
                    </div>
                    @endif
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
    const attachmentInput = document.getElementById('jobAttachments');
    const attachmentPreview = document.getElementById('attachmentPreview');
    const tempAttachmentsInputs = document.getElementById('tempAttachmentsInputs');
    const uploadUrl = attachmentInput ? attachmentInput.dataset.tempUploadUrl : null;
    const pageStateKey = 'druk_job_temp_attachments_state';
    const pageInstanceId = (() => {
        try {
            const existing = sessionStorage.getItem(pageStateKey);
            if (existing) {
                return existing;
            }

            const generated = `${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
            sessionStorage.setItem(pageStateKey, generated);
            return generated;
        } catch (error) {
            return `${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
        }
    })();
    const storageKey = 'druk_job_temp_attachments_' + pageInstanceId;

    let tempAttachments = [];

    function saveTempAttachments() {
        sessionStorage.setItem(storageKey, JSON.stringify(tempAttachments));
    }

    function renderTempAttachments() {
        if (!attachmentPreview || !tempAttachmentsInputs) {
            return;
        }

        attachmentPreview.innerHTML = '';
        tempAttachmentsInputs.innerHTML = '';

        tempAttachments.forEach((file) => {
            const chip = document.createElement('span');
            chip.className = 'badge bg-light text-dark border px-3 py-2';
            chip.innerHTML = '<i class="fa fa-paperclip me-1 text-primary"></i>' + file.name;
            attachmentPreview.appendChild(chip);

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'temp_attachments[]';
            input.value = file.path;
            tempAttachmentsInputs.appendChild(input);

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = 'temp_attachment_names[]';
            nameInput.value = file.name;
            tempAttachmentsInputs.appendChild(nameInput);
        });
    }

    function restoreTempAttachments() {
        try {
            tempAttachments = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
        } catch (error) {
            tempAttachments = [];
        }

        renderTempAttachments();
    }

    function clearTempAttachments() {
        try {
            sessionStorage.removeItem(storageKey);
            sessionStorage.removeItem(pageStateKey);
        } catch (error) {
            // ignore storage cleanup failures
        }
    }

    if (attachmentInput && uploadUrl) {
        restoreTempAttachments();

        attachmentInput.addEventListener('change', async function() {
            const files = Array.from(this.files || []);
            if (!files.length) {
                return;
            }

            for (const file of files) {
                try {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('attachment', file);

                    const response = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        continue;
                    }

                    const data = await response.json();
                    tempAttachments.push({ path: data.path, name: data.name, url: data.url });
                } catch (error) {
                    continue;
                }
            }

            this.value = '';
            saveTempAttachments();
            renderTempAttachments();
        });
    }

    window.addEventListener('pagehide', function() {
        const performanceEntries = performance.getEntriesByType('navigation');
        const navigationType = performanceEntries.length ? performanceEntries[0].type : '';

        // Preserve drafts on refresh, but clear them when the page is left normally.
        if (navigationType !== 'reload') {
            clearTempAttachments();
        }
    });

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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const datePicker = document.getElementById('deadline_picker');
    const hiddenDeadline = document.getElementById('deadline');
    const deadlineError = document.getElementById('deadlineError');

    function ddmmyyyyToIso(value) {
        // value: dd/mm/yyyy -> return yyyy-mm-dd
        if (!value) return '';
        const parts = value.split('/');
        if (parts.length !== 3) return '';
        const [d, m, y] = parts;
        if (d.length !== 2 || m.length !== 2 || y.length !== 4) return '';
        return `${y}-${m}-${d}`;
    }

    function isoToDdMmY(iso) {
        if (!iso) return '';
        const parts = iso.split('-');
        if (parts.length !== 3) return '';
        const [y, m, d] = parts;
        return `${d}/${m}/${y}`;
    }

    // Initialize date picker from hidden value (dd/mm/yyyy)
    if (datePicker && hiddenDeadline && hiddenDeadline.value) {
        const iso = ddmmyyyyToIso(hiddenDeadline.value);
        if (iso) datePicker.value = iso;
    }

    if (datePicker && hiddenDeadline) {
        const form = datePicker.closest('form');
        form.addEventListener('submit', function(e) {
            deadlineError.classList.add('d-none');
            deadlineError.classList.remove('d-block');

            const iso = datePicker.value; // yyyy-mm-dd
            if (!iso) {
                // empty -> clear hidden
                hiddenDeadline.value = '';
                return; // allow submit; server may accept null
            }

            const selected = new Date(iso + 'T00:00:00');
            const today = new Date();
            // Normalize times
            today.setHours(0,0,0,0);

            if (!(selected instanceof Date) || isNaN(selected.getTime())) {
                e.preventDefault();
                deadlineError.textContent = 'Invalid date selected.';
                deadlineError.classList.remove('d-none');
                deadlineError.classList.add('d-block');
                return;
            }

            // Server requires after:today (strictly greater than today)
            if (selected <= today) {
                e.preventDefault();
                deadlineError.textContent = 'Please select a date after today.';
                deadlineError.classList.remove('d-none');
                deadlineError.classList.add('d-block');
                return;
            }

            // Set hidden input to dd/mm/yyyy
            hiddenDeadline.value = isoToDdMmY(iso);
        });
    }
});
</script>
@endpush
