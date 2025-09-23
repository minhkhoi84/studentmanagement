@extends('layout')
@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-md-12">
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3 id="studentCount">{{ \App\Models\Student::count() }}</h3>
                            <p class="mb-0">Total Students</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>0</h3>
                            <p class="mb-0">Teachers</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>0</h3>
                            <p class="mb-0">Courses</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>0</h3>
                            <p class="mb-0">Enrollments</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('students.create') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-user-plus fa-2x mb-2"></i>
                                <br>Add New Student
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('students.index') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <br>View All Students
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-warning btn-lg w-100" onclick="alert('Feature coming soon!')">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <br>Search Students
                            </button>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-info btn-lg w-100" onclick="alert('Feature coming soon!')">
                                <i class="fas fa-file-export fa-2x mb-2"></i>
                                <br>Export Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    @if(\App\Models\Student::count() > 0)
                        <div class="activity-list">
                            @foreach(\App\Models\Student::latest()->take(8)->get() as $student)
                                <div class="activity-item">
                                    <div class="activity-icon bg-primary">
                                        <i class="fas fa-user-plus text-white"></i>
                                    </div>
                                    <div class="activity-content">
                                        <strong>{{ $student->name }}</strong> was added to the system
                                        <br>
                                        <small class="text-muted">{{ $student->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No recent activity</h5>
                            <p class="text-muted">Start by adding your first student!</p>
                            <a href="{{ route('students.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Student
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .welcome-header {
        text-align: center;
        padding: 2rem 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        margin-bottom: 2rem;
    }
    
    .welcome-header h1 {
        color: white !important;
    }
    
    .stat-card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-icon {
        opacity: 0.8;
    }
    
    .card {
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    
    .btn-lg {
        padding: 1.5rem;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .btn-lg:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .activity-list {
        max-height: 300px;
        overflow-y: auto;
    }
    
    .activity-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    
    .activity-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
    }
    
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate stat cards on load
        const statCards = document.querySelectorAll('.stat-card');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
        
        // Update student count dynamically
        setInterval(function() {
            // This would typically fetch from an API
            // For now, we'll just add some animation
            const countElement = document.getElementById('studentCount');
            countElement.style.transform = 'scale(1.1)';
            setTimeout(() => {
                countElement.style.transform = 'scale(1)';
            }, 200);
        }, 30000); // Every 30 seconds
    });
</script>
@endpush
