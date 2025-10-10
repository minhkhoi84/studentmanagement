@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Department Details Card -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-university"></i> Department Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('departments.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Departments List
                        </a>
                        <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Department
                        </a>
                        <button class="btn btn-success ms-2" onclick="printDepartment()">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>

                    <div class="row">
                        <!-- Department Icon -->
                        <div class="col-md-3 text-center">
                            <div class="department-icon">
                                <div class="icon-placeholder">
                                    <i class="fas fa-university fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-primary">Department ID: #{{ $department->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Created: {{ $department->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Department Information -->
                        <div class="col-md-9">
                            <div class="department-info">
                                <h2 class="text-primary mb-3">{{ $department->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-university text-primary"></i> Department Name
                                            </label>
                                            <div class="info-value">{{ $department->name }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-code text-success"></i> Department Code
                                            </label>
                                            <div class="info-value">
                                                <code class="bg-light p-2 rounded">{{ $department->code }}</code>
                                                <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $department->code }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user-tie text-info"></i> Dean
                                            </label>
                                            <div class="info-value">
                                                @if($department->dean)
                                                    {{ $department->dean }}
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
                                                <span class="badge bg-{{ $department->status === 'active' ? 'success' : 'secondary' }}">
                                                    {{ ucfirst($department->status ?? 'inactive') }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Created Date
                                            </label>
                                            <div class="info-value">{{ $department->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>

                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $department->updated_at->format('F d, Y \a\t h:i A') }}</div>
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
                                                @if($department->description)
                                                    {{ $department->description }}
                                                @else
                                                    <span class="text-muted">No description provided</span>
                                                @endif
                                            </div>
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
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-door-open fa-2x text-success mb-3"></i>
                            <h5>Classes</h5>
                            <h3 class="text-success">{{ $department->classes()->count() }}</h3>
                            <p class="text-muted">Total classes in this department</p>
                            <a href="{{ route('classes.index', ['department' => $department->id]) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-eye"></i> View Classes
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <i class="fas fa-chalkboard-teacher fa-2x text-info mb-3"></i>
                            <h5>Teachers</h5>
                            <h3 class="text-info">{{ $department->teachers()->count() }}</h3>
                            <p class="text-muted">Teachers in this department</p>
                            <a href="{{ route('teachers.index', ['department' => $department->name]) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i> View Teachers
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-user-graduate fa-2x text-warning mb-3"></i>
                            <h5>Students</h5>
                            <h3 class="text-warning">{{ $department->students()->count() }}</h3>
                            <p class="text-muted">Students in this department</p>
                            <a href="{{ route('students.index', ['department' => $department->name]) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-eye"></i> View Students
                            </a>
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
                            <h5>Edit Department</h5>
                            <p class="text-muted">Update department information</p>
                            <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Department
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-plus fa-2x text-success mb-3"></i>
                            <h5>Add Class</h5>
                            <p class="text-muted">Create a new class in this department</p>
                            <a href="{{ route('classes.create', ['department' => $department->id]) }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Add Class
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Department</h5>
                            <p class="text-muted">Permanently remove department</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Department
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display: none;">
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
    
    .department-icon {
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
    
    .border-info {
        border-color: #17a2b8 !important;
    }
    
    .border-warning {
        border-color: #ffc107 !important;
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
    // Print department function
    function printDepartment() {
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
        if (confirm('⚠️ WARNING: This will permanently delete "{{ $department->name }}"!\n\nAre you absolutely sure?')) {
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
