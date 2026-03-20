@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h3 class="mb-1"><i class="fa fa-user-edit text-primary me-2"></i>Edit Profile</h3>
                <p class="text-muted small mb-0">Update your professional profile and settings</p>
            </div>
            <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-secondary">
                <i class="fa fa-eye me-2"></i>View Public Profile
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3"><i class="fa fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-3"><i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-3">
            <strong><i class="fa fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <ul class="nav nav-tabs mb-4" id="profileTabs">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#tab-personal"><i class="fa fa-user me-1"></i>Personal Info</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-skills"><i class="fa fa-tools me-1"></i>Skills</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-docs"><i class="fa fa-id-card me-1"></i>Verification</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#tab-phone"><i class="fa fa-mobile-alt me-1"></i>Phone</a></li>
        </ul>

        <div class="tab-content">

            {{-- ── Personal Info ─────────────────────────────────── --}}
            <div class="tab-pane fade show active" id="tab-personal">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            {{-- Avatar --}}
                            <div class="d-flex align-items-start gap-4 mb-4">
                                <div class="text-center" style="min-width:110px">
                                    <img id="avatarPreview"
                                         src="{{ auth()->user()->avatar_url }}"
                                         class="rounded-circle border shadow-sm"
                                         style="width:100px;height:100px;object-fit:cover;"
                                         alt="Your photo">
                                    <div class="mt-2">
                                        <label class="btn btn-sm btn-outline-primary w-100" style="cursor:pointer">
                                            <i class="fa fa-camera me-1"></i>Change Photo
                                            <input type="file" name="avatar" class="d-none" accept="image/jpeg,image/png,image/jpg" onchange="previewAvatar(this)">
                                        </label>
                                        <div class="text-muted mt-1" style="font-size:10px">JPG/PNG, max 2 MB</div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">{{ auth()->user()->name }}</h6>
                                    <div class="text-muted small">{{ auth()->user()->email }}</div>
                                    <div class="mt-1">
                                        <span class="badge bg-{{ auth()->user()->verification_status === 'verified' ? 'success' : 'secondary' }}">
                                            <i class="fa fa-{{ auth()->user()->verification_status === 'verified' ? 'check-circle' : 'clock' }} me-1"></i>
                                            {{ ucfirst(auth()->user()->verification_status ?? 'Unverified') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="mb-4">

                            {{-- Basic Details --}}
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Basic Details</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Professional Headline</label>
                                    <input type="text" name="headline" class="form-control @error('headline') is-invalid @enderror"
                                           value="{{ old('headline', auth()->user()->profile?->headline) }}"
                                           placeholder="e.g. Full Stack Developer | Graphic Designer">
                                    @error('headline')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted small">+975</span>
                                        <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone', auth()->user()->phone) }}"
                                               placeholder="17XXXXXX">
                                    </div>
                                    @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Hourly Rate (Nu.)</label>
                                    <div class="input-group">
                                        <span class="input-group-text text-muted small">Nu.</span>
                                        <input type="number" name="hourly_rate" class="form-control @error('hourly_rate') is-invalid @enderror"
                                               value="{{ old('hourly_rate', auth()->user()->profile?->hourly_rate) }}" min="0">
                                    </div>
                                    @error('hourly_rate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Years of Experience</label>
                                    <input type="number" name="experience_years" class="form-control"
                                           value="{{ old('experience_years', auth()->user()->profile?->experience_years) }}"
                                           min="0" max="60" placeholder="e.g. 3">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Availability</label>
                                    <select name="availability" class="form-select">
                                        <option value="">Select...</option>
                                        <option value="available" @selected(old('availability', auth()->user()->profile?->availability) === 'available')>Available for Work</option>
                                        <option value="busy" @selected(old('availability', auth()->user()->profile?->availability) === 'busy')>Busy / Limited Availability</option>
                                        <option value="not_available" @selected(old('availability', auth()->user()->profile?->availability) === 'not_available')>Not Available</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Bio / About Me</label>
                                    <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4"
                                              placeholder="Tell clients about your experience, skills, and what makes you unique...">{{ old('bio', auth()->user()->profile?->bio) }}</textarea>
                                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="text-muted" style="font-size:11px">Max 1000 characters</div>
                                </div>
                            </div>

                            {{-- Location --}}
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Location</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Dzongkhag</label>
                                    <select name="dzongkhag" class="form-select">
                                        <option value="">Select Dzongkhag</option>
                                        @foreach($dzongkhags as $dz)
                                        <option value="{{ $dz }}" @selected(old('dzongkhag', auth()->user()->profile?->dzongkhag) === $dz)>{{ $dz }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Gewog</label>
                                    <input type="text" name="gewog" class="form-control"
                                           value="{{ old('gewog', auth()->user()->profile?->gewog) }}"
                                           placeholder="Your gewog">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small fw-semibold">Full Address</label>
                                    <input type="text" name="address" class="form-control"
                                           value="{{ old('address', auth()->user()->profile?->address) }}"
                                           placeholder="Street / village, town">
                                </div>
                            </div>

                            {{-- Online Presence --}}
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Online Presence</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Website / Portfolio URL</label>
                                    <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                                           value="{{ old('website', auth()->user()->profile?->website) }}"
                                           placeholder="https://yoursite.com">
                                    @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Preferred Language</label>
                                    <select name="preferred_language" class="form-select">
                                        <option value="en" @selected(old('preferred_language', auth()->user()->preferred_language) === 'en')>English</option>
                                        <option value="dz" @selected(old('preferred_language', auth()->user()->preferred_language) === 'dz')>Dzongkha</option>
                                    </select>
                                </div>
                            </div>

                            @if(auth()->user()->isJobPoster())
                            {{-- Company Info --}}
                            <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">Company / Organisation</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                           value="{{ old('company_name', auth()->user()->profile?->company_name) }}"
                                           placeholder="e.g. Druk Holdings & Investments">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Industry</label>
                                    <input type="text" name="industry" class="form-control"
                                           value="{{ old('industry', auth()->user()->profile?->industry) }}"
                                           placeholder="e.g. Technology, Finance">
                                </div>
                            </div>
                            @endif

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fa fa-save me-1"></i>Save Changes
                                </button>
                                <a href="{{ route('profile.show', auth()->user()) }}" class="btn btn-outline-secondary">
                                    <i class="fa fa-eye me-1"></i>View Profile
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Skills ─────────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-skills">
                <div class="card">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf @method('PUT')
                            <p class="text-muted small mb-4">Select the skills that best describe your expertise. Your skills appear on your public profile and help clients find you.</p>
                            @if($categories->isEmpty())
                            <div class="text-center text-muted py-4"><i class="fa fa-info-circle me-1"></i>No skills available yet.</div>
                            @else
                            @foreach($categories as $category)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-2 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">{{ $category->name }}</h6>
                                <div class="row g-2">
                                    @foreach($category->skills as $skill)
                                    <div class="col-sm-4 col-md-3">
                                        <div class="form-check border rounded px-3 py-2 {{ auth()->user()->skills->contains($skill->id) ? 'border-primary bg-primary bg-opacity-10' : '' }}">
                                            <input class="form-check-input" type="checkbox"
                                                   name="skills[]" value="{{ $skill->id }}"
                                                   id="skill{{ $skill->id }}"
                                                   @checked(in_array($skill->id, old('skills', auth()->user()->skills->pluck('id')->toArray())))>
                                            <label class="form-check-label small" for="skill{{ $skill->id }}">{{ $skill->name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                            @endif
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save me-1"></i>Update Skills</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ── Verification Docs ──────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-docs">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary bg-gradient text-white py-3">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-shield-alt me-2 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Account Verification</h6>
                                <small class="opacity-75">Verify your identity to build trust and unlock premium features</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        {{-- Verification Status Banner --}}
                        <div class="alert alert-{{ auth()->user()->verification_status === 'verified' ? 'success' : (auth()->user()->verification_status === 'pending' ? 'warning' : 'info') }} d-flex align-items-center mb-4">
                            <i class="fa fa-{{ auth()->user()->verification_status === 'verified' ? 'check-circle' : (auth()->user()->verification_status === 'pending' ? 'hourglass-half' : 'info-circle') }} me-3 fs-4"></i>
                            <div class="flex-grow-1">
                                <strong class="d-block">
                                    @if(auth()->user()->verification_status === 'verified')
                                        Your account is verified!
                                    @elseif(auth()->user()->verification_status === 'pending')
                                        Verification in progress
                                    @else
                                        Get your account verified
                                    @endif
                                </strong>
                                <small>
                                    @if(auth()->user()->verification_status === 'verified')
                                        You have full access to all platform features.
                                    @elseif(auth()->user()->verification_status === 'pending')
                                        Our team is reviewing your documents. You'll be notified once approved.
                                    @else
                                        Submit your documents to get verified. Verified accounts get more visibility and trust.
                                    @endif
                                </small>
                            </div>
                        </div>

                        {{-- Benefits Section --}}
                        <div class="mb-4 p-3 bg-light rounded">
                            <h6 class="fw-bold mb-2"><i class="fa fa-star text-warning me-1"></i> Benefits of Verification</h6>
                            <div class="row g-2 small">
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Verified badge on your profile
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Higher search ranking
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Build client trust & credibility
                                </div>
                                <div class="col-md-6">
                                    <i class="fa fa-check text-success me-1"></i> Access to premium features
                                </div>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-3 text-muted text-uppercase" style="font-size:11px;letter-spacing:1px">
                            <i class="fa fa-file-upload me-1"></i> Required Documents
                        </h6>

                        {{-- Document Upload Forms --}}
                        @php
                            $documentTypes = [
                                [
                                    'type' => 'cid',
                                    'label' => 'Citizenship ID (CID)',
                                    'icon' => 'fa-id-card',
                                    'description' => 'Upload a clear photo or scan of your Bhutanese CID (both front and back)',
                                    'placeholder' => 'CID Number (e.g., 11509000001)',
                                    'required' => true
                                ],
                                [
                                    'type' => 'license',
                                    'label' => 'Professional License / BRN',
                                    'icon' => 'fa-certificate',
                                    'description' => 'Business Registration Number or professional license certificate',
                                    'placeholder' => 'License/BRN Number',
                                    'required' => auth()->user()->isJobPoster()
                                ],
                                [
                                    'type' => 'education',
                                    'label' => 'Education Certificate',
                                    'icon' => 'fa-graduation-cap',
                                    'description' => 'Highest education qualification or relevant certification',
                                    'placeholder' => 'Certificate Number (optional)',
                                    'required' => false
                                ],
                                [
                                    'type' => 'tax_certificate',
                                    'label' => 'Tax Clearance Certificate',
                                    'icon' => 'fa-file-invoice',
                                    'description' => 'Valid tax clearance from Department of Revenue and Customs',
                                    'placeholder' => 'TPN/CID Number',
                                    'required' => false
                                ],
                            ];
                        @endphp

                        @foreach($documentTypes as $docType)
                            @php $doc = auth()->user()->verificationDocuments->where('document_type', $docType['type'])->first(); @endphp
                            <div class="card mb-3 {{ $doc && $doc->status==='approved' ? 'border-success' : '' }}">
                                <div class="card-header bg-light py-2 px-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <i class="fa {{ $docType['icon'] }} me-2 text-primary"></i>
                                            <span class="fw-semibold">{{ $docType['label'] }}</span>
                                            @if($docType['required'])
                                                <span class="badge bg-danger ms-2" style="font-size:9px">REQUIRED</span>
                                            @endif
                                        </div>
                                        @if($doc)
                                            <span class="badge bg-{{ $doc->status==='approved' ? 'success' : ($doc->status==='rejected' ? 'danger' : 'warning text-dark') }}">
                                                <i class="fa fa-{{ $doc->status==='approved' ? 'check-circle' : ($doc->status==='rejected' ? 'times-circle' : 'clock') }} me-1"></i>{{ ucfirst($doc->status) }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Not Uploaded</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body p-3">
                                    <p class="text-muted small mb-3">{{ $docType['description'] }}</p>
                                    
                                    @if($doc && $doc->status === 'rejected')
                                        <div class="alert alert-danger py-2 small mb-3">
                                            <strong><i class="fa fa-exclamation-triangle me-1"></i> Rejection Reason:</strong><br>
                                            {{ $doc->rejection_reason }}
                                        </div>
                                    @endif

                                    @if($doc && $doc->status === 'approved')
                                        <div class="d-flex align-items-center text-success">
                                            <i class="fa fa-check-circle me-2 fs-5"></i>
                                            <div>
                                                <strong>Document Approved</strong><br>
                                                <small class="text-muted">
                                                    Verified on {{ $doc->reviewed_at->format('d M Y, h:i A') }}
                                                    @if($doc->document_number)
                                                        • {{ $doc->document_number }}
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    @elseif($doc && $doc->status === 'pending')
                                        <div class="d-flex align-items-center text-warning">
                                            <i class="fa fa-hourglass-half me-2 fs-5"></i>
                                            <div>
                                                <strong>Under Review</strong><br>
                                                <small class="text-muted">Submitted {{ $doc->created_at->diffForHumans() }} • Our team typically reviews within 1-2 business days</small>
                                            </div>
                                        </div>
                                    @else
                                        {{-- Upload Form --}}
                                        <form method="POST" action="{{ route('profile.documents') }}" enctype="multipart/form-data" class="needs-validation" novalidate>
                                            @csrf
                                            <input type="hidden" name="document_type" value="{{ $docType['type'] }}">
                                            <div class="row g-3">
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-semibold">Document Number</label>
                                                    <input type="text" name="document_number" class="form-control form-control-sm" 
                                                           placeholder="{{ $docType['placeholder'] }}"
                                                           {{ $docType['type'] === 'cid' ? 'required' : '' }}>
                                                    <div class="invalid-feedback">Please enter document number.</div>
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label small fw-semibold">Upload File <span class="text-danger">*</span></label>
                                                    <input type="file" name="document_file" class="form-control form-control-sm" 
                                                           accept=".pdf,.jpg,.jpeg,.png" required>
                                                    <div class="form-text" style="font-size:10px">PDF, JPG, PNG (max 5 MB)</div>
                                                    <div class="invalid-feedback">Please select a file.</div>
                                                </div>
                                                <div class="col-md-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="fa fa-upload me-1"></i>Upload
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Instructions --}}
                        <div class="mt-4 p-3 bg-light rounded">
                            <h6 class="fw-bold mb-2"><i class="fa fa-lightbulb text-warning me-1"></i> Document Guidelines</h6>
                            <ul class="small mb-0 ps-3">
                                <li>Ensure documents are clear, readable, and not blurred</li>
                                <li>All four corners of the document should be visible</li>
                                <li>Documents must be valid and not expired</li>
                                <li>File size should not exceed 5 MB</li>
                                <li>Accepted formats: PDF, JPG, JPEG, PNG</li>
                                <li>Personal information must match your profile name</li>
                            </ul>
                        </div>

                        {{-- Privacy Notice --}}
                        <div class="alert alert-secondary small mt-3 mb-0">
                            <i class="fa fa-lock me-1"></i> <strong>Privacy Notice:</strong> Your documents are securely stored and only reviewed by authorized administrators. We will never share your personal information with third parties.
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Phone OTP ───────────────────────────────────────── --}}
            <div class="tab-pane fade" id="tab-phone">
                <div class="card">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-1">Phone Verification</h6>
                        <p class="text-muted small mb-4">Verify your Bhutanese mobile number to unlock additional platform features.</p>

                        @if(auth()->user()->phone_verified_at)
                        <div class="alert alert-success mb-0">
                            <i class="fa fa-check-circle me-2"></i>Phone <strong>{{ auth()->user()->phone }}</strong> verified!
                        </div>
                        @else
                        @if(!session('phone_otp_sent'))
                        <form method="POST" action="{{ route('profile.phone.otp') }}">
                            @csrf
                            <div class="mb-3" style="max-width:300px">
                                <label class="form-label small fw-semibold">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text">+975</span>
                                    <input type="tel" name="phone" class="form-control"
                                           value="{{ old('phone', auth()->user()->phone) }}"
                                           placeholder="17XXXXXX" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-sms me-1"></i>Send OTP</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('profile.phone.verify') }}">
                            @csrf
                            <p class="text-muted small">Enter the 6-digit code sent to your phone.</p>
                            <div class="mb-3" style="max-width:200px">
                                <input type="text" name="otp"
                                       class="form-control text-center fw-bold fs-4 @error('otp') is-invalid @enderror"
                                       maxlength="6" autofocus placeholder="——————" required>
                                @error('otp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check me-1"></i>Verify Phone</button>
                        </form>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>{{-- end tab-content --}}
    </div>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Image must be under 2 MB.');
            input.value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
