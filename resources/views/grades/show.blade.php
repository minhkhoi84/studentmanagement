@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Grade Details Card -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-chart-line"></i> Grade Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Grades List
                        </a>
                        <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Grade
                        </a>
                        <button class="btn btn-success ms-2" onclick="printGrade()">
                            <i class="fas fa-print"></i> Print Grade
                        </button>
                    </div>

                    <div class="row">
                        <!-- Grade Icon -->
                        <div class="col-md-3 text-center">
                            <div class="grade-icon">
                                <div class="icon-placeholder">
                                    <i class="fas fa-chart-line fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-warning">Grade ID: #{{ $grade->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Created: {{ $grade->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Grade Information -->
                        <div class="col-md-9">
                            <div class="grade-info">
                                <h2 class="text-warning mb-3">Grade Record</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user-graduate text-primary"></i> Student
                                            </label>
                                            <div class="info-value">
                                                <a href="{{ route('students.show', $grade->student->id) }}" class="text-decoration-none">
                                                    {{ $grade->student->name }}
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-book text-success"></i> Course
                                            </label>
                                            <div class="info-value">
                                                <a href="{{ route('courses.show', $grade->course->id) }}" class="text-decoration-none">
                                                    {{ $grade->course->name }}
                                                </a>
                                                <small class="text-muted d-block">({{ $grade->course->code }})</small>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-chalkboard-teacher text-info"></i> Teacher
                                            </label>
                                            <div class="info-value">
                                                @if($grade->course->teacher)
                                                    <a href="{{ route('teachers.show', $grade->course->teacher->id) }}" class="text-decoration-none">
                                                        {{ $grade->course->teacher->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Created Date
                                            </label>
                                            <div class="info-value">{{ $grade->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $grade->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Scores Section -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <h4 class="text-primary mb-3">
                                            <i class="fas fa-calculator"></i> Scores
                                        </h4>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="score-card bg-light p-3 rounded">
                                            <div class="text-center">
                                                <h5 class="text-primary">Midterm Score</h5>
                                                <div class="score-display">
                                                    @if($grade->midterm_score !== null)
                                                        <span class="badge bg-primary fs-4">{{ $grade->midterm_score }}/10</span>
                                                    @else
                                                        <span class="text-muted">Not available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="score-card bg-light p-3 rounded">
                                            <div class="text-center">
                                                <h5 class="text-success">Final Score</h5>
                                                <div class="score-display">
                                                    @if($grade->final_score !== null)
                                                        <span class="badge bg-success fs-4">{{ $grade->final_score }}/10</span>
                                                    @else
                                                        <span class="text-muted">Not available</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="score-card bg-light p-3 rounded">
                                            <div class="text-center">
                                                <h5 class="text-warning">Total Score</h5>
                                                <div class="score-display">
                                                    @if($grade->total_score !== null)
                                                        <span class="badge bg-warning text-dark fs-4">{{ $grade->total_score }}/10</span>
                                                    @else
                                                        <span class="text-muted">Not calculated</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-trophy text-warning"></i> Grade Letter
                                            </label>
                                            <div class="info-value">
                                                @if($grade->grade)
                                                    <span class="badge bg-{{ $grade->grade === 'A' ? 'success' : ($grade->grade === 'B' ? 'primary' : ($grade->grade === 'C' ? 'warning' : ($grade->grade === 'D' ? 'info' : 'danger'))) }} fs-5">
                                                        {{ $grade->grade }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-check-circle text-success"></i> Status
                                            </label>
                                            <div class="info-value">
                                                @if($grade->status)
                                                    <span class="badge bg-{{ $grade->status === 'passed' ? 'success' : 'danger' }} fs-6">
                                                        {{ ucfirst($grade->status) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not determined</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($grade->notes)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-sticky-note text-secondary"></i> Notes
                                            </label>
                                            <div class="info-value notes-box">
                                                {{ $grade->notes }}
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

            <!-- Action Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-edit fa-2x text-primary mb-3"></i>
                            <h5>Edit Grade</h5>
                            <p class="text-muted">Update scores and grade information</p>
                            <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Grade
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-share-alt fa-2x text-success mb-3"></i>
                            <h5>Share Grade</h5>
                            <p class="text-muted">Share grade information with student</p>
                            <button class="btn btn-success" onclick="shareGrade()">
                                <i class="fas fa-share"></i> Share Grade
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Grade</h5>
                            <p class="text-muted">Permanently remove grade record</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Grade
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display: none;">
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
    
    .grade-icon {
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
        border-left: 4px solid #ffc107;
    }
    
    .score-card {
        border: 2px solid #dee2e6;
        transition: transform 0.2s ease;
    }
    
    .score-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .score-display {
        margin-top: 1rem;
    }
    
    .notes-box {
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
    // Print grade function
    function printGrade() {
        window.print();
    }
    
    // Share grade function
    function shareGrade() {
        if (navigator.share) {
            navigator.share({
                title: 'Grade Report: {{ $grade->student->name }} - {{ $grade->course->name }}',
                text: 'Check out this grade report',
                url: window.location.href
            });
        } else {
            // Fallback - copy URL to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Grade URL copied to clipboard!');
            });
        }
    }
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete this grade record!\n\nAre you absolutely sure?')) {
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
