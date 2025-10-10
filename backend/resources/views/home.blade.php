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

    <!-- Charts Section -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Biểu đồ thống kê lượng sinh viên nghỉ học
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 400px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Biểu đồ đánh điểm học tập
                    </h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 400px;">
                        <canvas id="gradeChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
// Đợi Chart.js load xong
window.addEventListener('load', function() {
    console.log('Chart.js loaded, initializing charts...');
    
    // Kiểm tra xem Chart có tồn tại không
    if (typeof Chart === 'undefined') {
        console.error('Chart.js not loaded!');
        return;
    }

    // Biểu đồ thống kê sinh viên nghỉ học
    const attendanceCtx = document.getElementById('attendanceChart');
    if (!attendanceCtx) {
        console.error('attendanceChart canvas not found!');
        return;
    }

    const attendanceChart = new Chart(attendanceCtx, {
        type: 'pie',
        data: {
            labels: [
                'Sinh viên nghỉ > 5',
                'Sinh viên nghỉ = 4', 
                'Sinh viên nghỉ = 3',
                'Sinh viên nghỉ = 2',
                'Sinh viên nghỉ <= 1'
            ],
            datasets: [{
                data: [0, 0, 0, 0, 4], // Dữ liệu mẫu - sẽ được cập nhật từ API
                backgroundColor: [
                    '#dc3545', // Đỏ
                    '#fd7e14', // Cam
                    '#6f42c1', // Tím
                    '#0d6efd', // Xanh dương
                    '#198754'  // Xanh lá
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Biểu đồ đánh điểm học tập
    const gradeCtx = document.getElementById('gradeChart');
    if (!gradeCtx) {
        console.error('gradeChart canvas not found!');
        return;
    }

    const gradeChart = new Chart(gradeCtx, {
        type: 'pie',
        data: {
            labels: [
                'Điểm < 5',
                'Điểm >= 5 và < 8',
                'Điểm >= 8'
            ],
            datasets: [{
                data: [3, 1, 1], // Dữ liệu mẫu - sẽ được cập nhật từ API
                backgroundColor: [
                    '#dc3545', // Đỏ
                    '#0dcaf0', // Xanh dương nhạt
                    '#198754'  // Xanh lá
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    console.log('Charts initialized successfully!');
    
    // Load real data
    loadChartData();
});

// Function để load dữ liệu thực từ server
function loadChartData() {
    console.log('Loading chart data...');
    
    fetch('/api/statistics')
        .then(response => {
            console.log('API response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('API data received:', data);
            
            // Cập nhật dữ liệu cho biểu đồ attendance
            if (data.success && data.data && data.data.attendance) {
                const attendanceChart = Chart.getChart('attendanceChart');
                if (attendanceChart) {
                    attendanceChart.data.datasets[0].data = [
                        data.data.attendance.absent_more_than_5 || 0,
                        data.data.attendance.absent_4 || 0,
                        data.data.attendance.absent_3 || 0,
                        data.data.attendance.absent_2 || 0,
                        data.data.attendance.absent_1_or_less || 0
                    ];
                    attendanceChart.update();
                    console.log('Attendance chart updated');
                }
            }

            // Cập nhật dữ liệu cho biểu đồ grade
            if (data.success && data.data && data.data.grades) {
                const gradeChart = Chart.getChart('gradeChart');
                if (gradeChart) {
                    gradeChart.data.datasets[0].data = [
                        data.data.grades.below_5 || 0,
                        data.data.grades.between_5_8 || 0,
                        data.data.grades.above_8 || 0
                    ];
                    gradeChart.update();
                    console.log('Grade chart updated');
                }
            }
        })
        .catch(error => {
            console.error('Không thể load dữ liệu thống kê:', error);
            // Sử dụng dữ liệu mẫu nếu không load được
        });
}
</script>
@endpush

