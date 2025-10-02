@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Student Profile Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle"></i> Student Profile Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Students List
                        </a>
                    </div>

                    <div class="row">
                        <!-- Student Avatar -->
                        <div class="col-md-3 text-center">
                            <div class="student-avatar">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user-graduate fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-primary">Student ID: #{{ $student->id }}</h5>
                                @if($student->student_code)
                                <p class="text-info">
                                    <i class="fas fa-id-card"></i> 
                                    Mã SV: {{ $student->student_code }}
                                </p>
                                @endif
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Joined: {{ $student->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Student Information -->
                        <div class="col-md-9">
                            <div class="student-info">
                                <h2 class="text-primary mb-3">{{ $student->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user text-primary"></i> Full Name
                                            </label>
                                            <div class="info-value">{{ $student->name }}</div>
                                        </div>
                                        
                                        @if($student->student_code)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-id-card text-info"></i> Student Code
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-info">{{ $student->student_code }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($student->date_of_birth)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-birthday-cake text-warning"></i> Date of Birth
                                            </label>
                                            <div class="info-value">
                                                {{ $student->date_of_birth->format('d/m/Y') }}
                                                @if($student->age)
                                                <span class="text-muted">({{ $student->age }} tuổi)</span>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($student->gender)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-venus-mars text-pink"></i> Gender
                                            </label>
                                            <div class="info-value">{{ $student->gender_display }}</div>
                                        </div>
                                        @endif
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-phone text-success"></i> Mobile Number
                                            </label>
                                            <div class="info-value">
                                                <a href="tel:{{ $student->mobile }}" class="text-decoration-none">
                                                    {{ $student->mobile }}
                                                </a>
                                                <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $student->mobile }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        @if($student->email)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-envelope text-primary"></i> Email
                                            </label>
                                            <div class="info-value">
                                                <a href="mailto:{{ $student->email }}" class="text-decoration-none">
                                                    {{ $student->email }}
                                                </a>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($student->nationality)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-flag text-success"></i> Quê Quán
                                            </label>
                                            <div class="info-value">{{ $student->nationality }}</div>
                                        </div>
                                        @endif
                                        
                                        @if($student->class)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-graduation-cap text-info"></i> Class
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-secondary">{{ $student->class }}</span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        @if($student->major)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-book text-warning"></i> Major
                                            </label>
                                            <div class="info-value">{{ $student->major }}</div>
                                        </div>
                                        @endif
                                        
                                        @if($student->status)
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-info-circle text-info"></i> Status
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $student->status_badge_color }}">
                                                    {{ ucfirst($student->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @endif
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Registration Date
                                            </label>
                                            <div class="info-value">{{ $student->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $student->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-map-marker-alt text-danger"></i> Address
                                            </label>
                                            <div class="info-value address-box">
                                                {{ $student->address }}
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="openMaps('{{ urlencode($student->address) }}')">
                                                    <i class="fas fa-map"></i> View on Map
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($student->father_name || $student->mother_name)
                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="text-primary mb-3">
                                            <i class="fas fa-users"></i> Family Information
                                        </h5>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    @if($student->father_name)
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-male text-primary"></i> Father Name
                                            </label>
                                            <div class="info-value">{{ $student->father_name }}</div>
                                            @if($student->father_phone)
                                            <div class="text-muted small">
                                                <i class="fas fa-phone"></i> {{ $student->father_phone }}
                                            </div>
                                            @endif
                                            @if($student->father_occupation)
                                            <div class="text-muted small">
                                                <i class="fas fa-briefcase"></i> {{ $student->father_occupation }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($student->mother_name)
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-female text-pink"></i> Mother Name
                                            </label>
                                            <div class="info-value">{{ $student->mother_name }}</div>
                                            @if($student->mother_phone)
                                            <div class="text-muted small">
                                                <i class="fas fa-phone"></i> {{ $student->mother_phone }}
                                            </div>
                                            @endif
                                            @if($student->mother_occupation)
                                            <div class="text-muted small">
                                                <i class="fas fa-briefcase"></i> {{ $student->mother_occupation }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                @endif

                                @if($student->notes)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-sticky-note text-warning"></i> Notes
                                            </label>
                                            <div class="info-value">
                                                <div class="alert alert-light">
                                                    {{ $student->notes }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/students.css') }}">
@endpush

@push('js')
<script>
    
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Success feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }, 2000);
        });
    }
    
    // Open address in maps
    function openMaps(address) {
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${address}`;
        window.open(mapsUrl, '_blank');
    }
    
    
    // Add some interactivity on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Animate info items on scroll
        const infoItems = document.querySelectorAll('.info-item');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });
        
        infoItems.forEach(function(item) {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
    });
</script>
@endpush







