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
        <div class="col-md-2">
            <div class="stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>1</h3>
                            <p class="mb-0">Khoa</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-university fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-light text-info">Xem thêm ➤</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>1</h3>
                            <p class="mb-0">Lớp</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-door-open fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <button class="btn btn-sm btn-light text-success">Xem thêm ➤</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>{{ \App\Models\Student::count() }}</h3>
                            <p class="mb-0">Sinh viên</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('students.index') }}" class="btn btn-sm btn-light text-warning">Xem thêm ➤</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-2">
            <div class="stat-card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>{{ \App\Models\Course::count() }}</h3>
                            <p class="mb-0">Môn học</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-light text-danger">Xem thêm ➤</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-2">
            <div class="stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h3>{{ \App\Models\Teacher::count() }}</h3>
                            <p class="mb-0">Giảng viên</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chalkboard-teacher fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <a href="{{ route('teachers.index') }}" class="btn btn-sm btn-light text-warning">Xem thêm ➤</a>
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

