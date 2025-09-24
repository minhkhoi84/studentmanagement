@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Quản Lý Điểm Danh</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('attendances.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Sinh viên</label>
                        <select name="student_id" class="form-select">
                            <option value="">Tất cả sinh viên</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Môn học</label>
                        <select name="course_id" class="form-select">
                            <option value="">Tất cả môn học</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Ngày</label>
                        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="present" {{ request('status')=='present' ? 'selected' : '' }}>Có mặt</option>
                            <option value="absent" {{ request('status')=='absent' ? 'selected' : '' }}>Vắng</option>
                            <option value="late" {{ request('status')=='late' ? 'selected' : '' }}>Muộn</option>
                            <option value="excused" {{ request('status')=='excused' ? 'selected' : '' }}>Có phép</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('attendances.create') }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Điểm danh
                                </a>
                            @endif
                        @endauth
                        <button class="btn btn-primary btn-sm ms-1"><i class="fas fa-filter"></i> Lọc</button>
                        <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-clipboard-list"></i> Bảng Điểm Danh</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Ngày</th>
                    <th>Sinh Viên</th>
                    <th>Môn Học</th>
                    <th>Trạng Thái</th>
                    <th>Số Buổi Vắng</th>
                    <th>Ghi Chú</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($attendances as $i => $attendance)
                <tr>
                    <td>{{ $attendances->firstItem() + $i }}</td>
                    <td>
                        <strong>{{ $attendance->attendance_date->format('d/m/Y') }}</strong>
                        <br><small class="text-muted">{{ $attendance->attendance_date->format('l') }}</small>
                    </td>
                    <td>
                        <strong>{{ $attendance->student->name }}</strong>
                        <br><small class="text-muted">{{ $attendance->student->student_code ?? 'SV' . str_pad($attendance->student->id, 3, '0', STR_PAD_LEFT) }}</small>
                    </td>
                    <td>
                        <strong>{{ $attendance->course->name }}</strong>
                        <br><small class="text-muted">{{ $attendance->course->code }}</small>
                    </td>
                    <td>
                        @php
                            $statusColors = [
                                'present' => 'success',
                                'absent' => 'danger', 
                                'late' => 'warning',
                                'excused' => 'info'
                            ];
                            $statusTexts = [
                                'present' => 'Có mặt',
                                'absent' => 'Vắng',
                                'late' => 'Muộn', 
                                'excused' => 'Có phép'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$attendance->status] }}">
                            {{ $statusTexts[$attendance->status] }}
                        </span>
                    </td>
                    <td>
                        @php
                            $absentCount = \App\Models\Attendance::where('student_id', $attendance->student_id)
                                ->where('course_id', $attendance->course_id)
                                ->whereIn('status', ['absent', 'late'])
                                ->count();
                        @endphp
                        <span class="badge bg-{{ $absentCount > 3 ? 'danger' : ($absentCount > 1 ? 'warning' : 'success') }}">
                            {{ $absentCount }} buổi
                        </span>
                    </td>
                    <td>{{ $attendance->notes ?? '-' }}</td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('attendances.destroy', $attendance->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa điểm danh này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Chỉ xem</span>
                            @endif
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có điểm danh nào. Vui lòng thêm điểm danh mới.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
