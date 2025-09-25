@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Attendance Details Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list"></i> Attendance Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('attendances.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Attendance List
                        </a>
                        <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Attendance
                        </a>
                        <button class="btn btn-success ms-2" onclick="printAttendance()">
                            <i class="fas fa-print"></i> Print Record
                        </button>
                    </div>

                    <div class="row">
                        <!-- Attendance Icon -->
                        <div class="col-md-3 text-center">
                            <div class="attendance-icon">
                                <div class="icon-placeholder">
                                    <i class="fas fa-clipboard-list fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-info">Record ID: #{{ $attendance->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Created: {{ $attendance->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Attendance Information -->
                        <div class="col-md-9">
                            <div class="attendance-info">
                                <h2 class="text-info mb-3">Attendance Record</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user-graduate text-primary"></i> Student
                                            </label>
                                            <div class="info-value">
                                                <a href="{{ route('students.show', $attendance->student->id) }}" class="text-decoration-none">
                                                    {{ $attendance->student->name }}
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-book text-success"></i> Course
                                            </label>
                                            <div class="info-value">
                                                <a href="{{ route('courses.show', $attendance->course->id) }}" class="text-decoration-none">
                                                    {{ $attendance->course->name }}
                                                </a>
                                                <small class="text-muted d-block">({{ $attendance->course->code }})</small>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-chalkboard-teacher text-info"></i> Teacher
                                            </label>
                                            <div class="info-value">
                                                @if($attendance->course->teacher)
                                                    <a href="{{ route('teachers.show', $attendance->course->teacher->id) }}" class="text-decoration-none">
                                                        {{ $attendance->course->teacher->name }}
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
                                                <i class="fas fa-calendar text-warning"></i> Attendance Date
                                            </label>
                                            <div class="info-value">
                                                <strong>{{ $attendance->attendance_date->format('F d, Y') }}</strong>
                                                <small class="text-muted d-block">{{ $attendance->attendance_date->format('l') }}</small>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-check-circle text-success"></i> Status
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $attendance->status_color }} fs-6">
                                                    <i class="fas fa-{{ $attendance->status_icon }}"></i>
                                                    {{ $attendance->status_text }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Recorded Date
                                            </label>
                                            <div class="info-value">{{ $attendance->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                @if($attendance->notes)
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-sticky-note text-secondary"></i> Notes
                                            </label>
                                            <div class="info-value notes-box">
                                                {{ $attendance->notes }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $attendance->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-clock text-primary"></i> Day of Week
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-primary fs-6">
                                                    {{ $attendance->day_of_week_vietnamese }}
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

            <!-- Status Information Card -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Status Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="status-card text-center p-3 rounded">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <h6>Present</h6>
                                <p class="text-muted small">Student attended the class</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-card text-center p-3 rounded">
                                <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                                <h6>Late</h6>
                                <p class="text-muted small">Student arrived late</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-card text-center p-3 rounded">
                                <i class="fas fa-sticky-note fa-2x text-info mb-2"></i>
                                <h6>Excused</h6>
                                <p class="text-muted small">Student had valid excuse</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="status-card text-center p-3 rounded">
                                <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                                <h6>Absent</h6>
                                <p class="text-muted small">Student was not present</p>
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
                            <h5>Edit Attendance</h5>
                            <p class="text-muted">Update attendance status and notes</p>
                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Attendance
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-share-alt fa-2x text-success mb-3"></i>
                            <h5>Share Record</h5>
                            <p class="text-muted">Share attendance record with student</p>
                            <button class="btn btn-success" onclick="shareAttendance()">
                                <i class="fas fa-share"></i> Share Record
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Record</h5>
                            <p class="text-muted">Permanently remove attendance record</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Record
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display: none;">
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
    
    .attendance-icon {
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
        border-left: 4px solid #17a2b8;
    }
    
    .status-card {
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        transition: transform 0.2s ease;
    }
    
    .status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
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
    // Print attendance function
    function printAttendance() {
        window.print();
    }
    
    // Share attendance function
    function shareAttendance() {
        if (navigator.share) {
            navigator.share({
                title: 'Attendance Record: {{ $attendance->student->name }} - {{ $attendance->course->name }}',
                text: 'Check out this attendance record',
                url: window.location.href
            });
        } else {
            // Fallback - copy URL to clipboard
            navigator.clipboard.writeText(window.location.href).then(function() {
                alert('Attendance URL copied to clipboard!');
            });
        }
    }
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete this attendance record!\n\nAre you absolutely sure?')) {
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
