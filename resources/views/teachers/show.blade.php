@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Teacher Profile Card -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i> Teacher Profile Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Teachers List
                        </a>
                        <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Teacher
                        </a>
                        <button class="btn btn-success ms-2" onclick="printProfile()">
                            <i class="fas fa-print"></i> Print Profile
                        </button>
                    </div>

                    <div class="row">
                        <!-- Teacher Avatar -->
                        <div class="col-md-3 text-center">
                            <div class="teacher-avatar">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-chalkboard-teacher fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-success">Teacher ID: #{{ $teacher->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Joined: {{ $teacher->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Teacher Information -->
                        <div class="col-md-9">
                            <div class="teacher-info">
                                <h2 class="text-success mb-3">{{ $teacher->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user text-success"></i> Full Name
                                            </label>
                                            <div class="info-value">{{ $teacher->name }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-envelope text-primary"></i> Email
                                            </label>
                                            <div class="info-value">
                                                <a href="mailto:{{ $teacher->email }}" class="text-decoration-none">
                                                    {{ $teacher->email }}
                                                </a>
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $teacher->email }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-phone text-success"></i> Phone
                                            </label>
                                            <div class="info-value">
                                                @if($teacher->phone)
                                                    <a href="tel:{{ $teacher->phone }}" class="text-decoration-none">
                                                        {{ $teacher->phone }}
                                                    </a>
                                                    <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $teacher->phone }}')">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-university text-info"></i> Department
                                            </label>
                                            <div class="info-value">
                                                @if($teacher->department)
                                                    {{ $teacher->department }}
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-graduation-cap text-warning"></i> Qualification
                                            </label>
                                            <div class="info-value">
                                                @if($teacher->qualification)
                                                    {{ $teacher->qualification }}
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-flag text-danger"></i> Nationality
                                            </label>
                                            <div class="info-value">
                                                @if($teacher->nationality)
                                                    {{ $teacher->nationality }}
                                                @else
                                                    <span class="text-muted">Not provided</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Registration Date
                                            </label>
                                            <div class="info-value">{{ $teacher->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $teacher->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-info-circle text-primary"></i> Status
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($teacher->status ?? 'inactive') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-edit fa-2x text-primary mb-3"></i>
                            <h5>Edit Information</h5>
                            <p class="text-muted">Update teacher details and contact information</p>
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Teacher
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x text-success mb-3"></i>
                            <h5>View Courses</h5>
                            <p class="text-muted">See all courses taught by this teacher</p>
                            <a href="{{ route('courses.index', ['teacher' => $teacher->id]) }}" class="btn btn-success">
                                <i class="fas fa-book"></i> View Courses
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Teacher</h5>
                            <p class="text-muted">Permanently remove teacher from system</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Teacher
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    
    .teacher-avatar {
        padding: 2rem 1rem;
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 3px solid #dee2e6;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #495057;
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        border-left: 4px solid #28a745;
    }
    
    .border-primary {
        border-color: #007bff !important;
    }
    
    .border-success {
        border-color: #28a745 !important;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
    
    @media print {
        .btn, .card:not(:first-child) {
            display: none !important;
        }
        
        .card-body {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('js')
<script>
    // Print profile function
    function printProfile() {
        window.print();
    }
    
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Success feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-outline-primary', 'btn-outline-success');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        });
    }
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete {{ $teacher->name }}!\n\nAre you absolutely sure?')) {
            if (confirm('This action cannot be undone. Click OK to proceed with deletion.')) {
                document.getElementById('deleteForm').submit();
            }
        }
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
