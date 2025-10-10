@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Course Details Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-book-open"></i> Course Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('courses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Courses List
                        </a>
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Course
                        </a>
                        <button class="btn btn-success ms-2" onclick="printCourse()">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>

                    <div class="row">
                        <!-- Course Icon -->
                        <div class="col-md-3 text-center">
                            <div class="course-icon">
                                <div class="icon-placeholder">
                                    <i class="fas fa-book-open fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-primary">Course ID: #{{ $course->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Created: {{ $course->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Course Information -->
                        <div class="col-md-9">
                            <div class="course-info">
                                <h2 class="text-primary mb-3">{{ $course->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-book text-primary"></i> Course Name
                                            </label>
                                            <div class="info-value">{{ $course->name }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-code text-success"></i> Course Code
                                            </label>
                                            <div class="info-value">
                                                <code class="bg-light p-2 rounded">{{ $course->code }}</code>
                                                <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $course->code }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-graduation-cap text-warning"></i> Credits
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-warning text-dark fs-6">{{ $course->credits }} credits</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-chalkboard-teacher text-info"></i> Teacher
                                            </label>
                                            <div class="info-value">
                                                @if($course->teacher)
                                                    <a href="{{ route('teachers.show', $course->teacher->id) }}" class="text-decoration-none">
                                                        {{ $course->teacher->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-info-circle text-primary"></i> Status
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($course->status ?? 'inactive') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Created Date
                                            </label>
                                            <div class="info-value">{{ $course->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-align-left text-secondary"></i> Description
                                            </label>
                                            <div class="info-value description-box">
                                                @if($course->description)
                                                    {{ $course->description }}
                                                @else
                                                    <span class="text-muted">No description provided</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $course->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-users text-success"></i> Students Enrolled
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-info fs-6">
                                                    {{ $course->grades()->count() }} students
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
                            <h5>Edit Course</h5>
                            <p class="text-muted">Update course information and details</p>
                            <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Course
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-line fa-2x text-success mb-3"></i>
                            <h5>View Grades</h5>
                            <p class="text-muted">See all grades for this course</p>
                            <a href="{{ route('grades.index', ['course' => $course->id]) }}" class="btn btn-success">
                                <i class="fas fa-chart-line"></i> View Grades
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Course</h5>
                            <p class="text-muted">Permanently remove course from system</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Course
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display: none;">
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
    
    .course-icon {
        padding: 2rem 1rem;
    }
    
    .icon-placeholder {
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
        border-left: 4px solid #007bff;
    }
    
    .description-box {
        min-height: 60px;
        line-height: 1.5;
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
    // Print course function
    function printCourse() {
        window.print();
    }
    
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
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete "{{ $course->name }}"!\n\nAre you absolutely sure?')) {
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
