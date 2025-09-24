@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Quản Lý Điểm</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('grades.index') }}">
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
                        <label class="form-label">Kết quả</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="passed" {{ request('status')=='passed' ? 'selected' : '' }}>Đậu</option>
                            <option value="failed" {{ request('status')=='failed' ? 'selected' : '' }}>Rớt</option>
                            <option value="incomplete" {{ request('status')=='incomplete' ? 'selected' : '' }}>Chưa hoàn thành</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('grades.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Nhập điểm
                                </a>
                            @endif
                        @endauth
                        <button class="btn btn-primary ms-2"><i class="fas fa-filter"></i> Áp dụng</button>
                        <a href="{{ route('grades.index') }}" class="btn btn-outline-secondary ms-2">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-chart-line"></i> Bảng Điểm</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Sinh Viên</th>
                    <th>Môn Học</th>
                    <th>Điểm GK</th>
                    <th>Điểm CK</th>
                    <th>Điểm TB</th>
                    <th>Xếp Loại</th>
                    <th>Kết Quả</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $i => $grade)
                <tr>
                    <td>{{ $grades->firstItem() + $i }}</td>
                    <td>
                        <strong>{{ $grade->student->name }}</strong>
                        <br><small class="text-muted">{{ $grade->student->student_code ?? 'SV' . str_pad($grade->student->id, 3, '0', STR_PAD_LEFT) }}</small>
                    </td>
                    <td>
                        <strong>{{ $grade->course->name }}</strong>
                        <br><small class="text-muted">{{ $grade->course->code }}</small>
                    </td>
                    <td>{{ $grade->midterm_score ?? '-' }}</td>
                    <td>{{ $grade->final_score ?? '-' }}</td>
                    <td>
                        @if($grade->total_score)
                            <strong class="text-primary">{{ $grade->total_score }}</strong>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if($grade->grade)
                            <span class="badge bg-{{ $grade->grade === 'A' ? 'success' : ($grade->grade === 'F' ? 'danger' : 'warning') }}">
                                {{ $grade->grade }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ $grade->status === 'passed' ? 'success' : ($grade->status === 'failed' ? 'danger' : 'secondary') }}">
                            @if($grade->status === 'passed') Đậu
                            @elseif($grade->status === 'failed') Rớt
                            @else Chưa hoàn thành
                            @endif
                        </span>
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa điểm này không?')">
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
                    <td colspan="9" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Chưa có điểm nào. Vui lòng nhập điểm cho sinh viên.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $grades->links() }}
        </div>
    </div>
</div>
@endsection
