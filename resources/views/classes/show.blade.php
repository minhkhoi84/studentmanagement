@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Class Details Card -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-door-open"></i> Class Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Classes List
                        </a>
                        <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Class
                        </a>
                        <button class="btn btn-success ms-2" onclick="printClass()">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>

                    <div class="row">
                        <!-- Class Icon -->
                        <div class="col-md-3 text-center">
                            <div class="class-icon">
                                <div class="icon-placeholder">
                                    <i class="fas fa-door-open fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-success">Class ID: #{{ $class->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Created: {{ $class->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Class Information -->
                        <div class="col-md-9">
                            <div class="class-info">
                                <h2 class="text-success mb-3">{{ $class->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-door-open text-success"></i> Class Name
                                            </label>
                                            <div class="info-value">{{ $class->name }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-code text-primary"></i> Class Code
                                            </label>
                                            <div class="info-value">
                                                <code class="bg-light p-2 rounded">{{ $class->code }}</code>
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('{{ $class->code }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-university text-info"></i> Department
                                            </label>
                                            <div class="info-value">
                                                @if($class->department)
                                                    <a href="{{ route('departments.show', $class->department->id) }}" class="text-decoration-none">
                                                        {{ $class->department->name }}
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
                                                <i class="fas fa-info-circle text-primary"></i> Status
                                            </label>
                                            <div class="info-value">
                                                <span class="badge bg-{{ $class->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($class->status ?? 'inactive') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Created Date
                                            </label>
                                            <div class="info-value">{{ $class->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $class->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-2x text-primary mb-3"></i>
                            <h5>Students</h5>
                            <h3 class="text-primary">{{ $class->students()->count() }}</h3>
                            <p class="text-muted">Students enrolled in this class</p>
                            <a href="{{ route('students.index', ['class' => $class->name]) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-eye"></i> View Students
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-book fa-2x text-info mb-3"></i>
                            <h5>Courses</h5>
                            <h3 class="text-info">{{ $class->courses()->count() }}</h3>
                            <p class="text-muted">Courses available for this class</p>
                            <a href="{{ route('courses.index', ['class' => $class->name]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Courses
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Students Table -->
            @if($class->students()->count() > 0)
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Students in this Class
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($class->students()->take(10)->get() as $student)
                                <tr>
                                    <td>#{{ $student->id }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="text-decoration-none">
                                            {{ $student->name }}
                                        </a>
                                    </td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->mobile ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($student->status ?? 'inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($class->students()->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('students.index', ['class' => $class->name]) }}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View All Students ({{ $class->students()->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Action Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-edit fa-2x text-primary mb-3"></i>
                            <h5>Edit Class</h5>
                            <p class="text-muted">Update class information</p>
                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Class
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-user-plus fa-2x text-success mb-3"></i>
                            <h5>Add Student</h5>
                            <p class="text-muted">Enroll a new student in this class</p>
                            <a href="{{ route('students.create', ['class' => $class->name]) }}" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Add Student
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Class</h5>
                            <p class="text-muted">Permanently remove class</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Class
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('classes.destroy', $class->id) }}" method="POST" style="display: none;">
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
    
    .class-icon {
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
        border-left: 4px solid #28a745;
    }
    
    .border-primary {
        border-color: #007bff !important;
    }
    
    .border-success {
        border-color: #28a745 !important;
    }
    
    .border-info {
        border-color: #17a2b8 !important;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
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
    // Print class function
    function printClass() {
        window.print();
    }
    
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Success feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-outline-primary');
            btn.classList.add('btn-primary');
            
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            }, 2000);
        });
    }
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete "{{ $class->name }}"!\n\nAre you absolutely sure?')) {
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
