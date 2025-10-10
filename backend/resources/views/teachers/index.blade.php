@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Giảng viên</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('teachers.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên, email, số điện thoại">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        @auth
                            @if(Auth::user()->hasPermission('create-teachers'))
                                <a href="{{ route('teachers.create') }}" class="btn btn-primary">Thêm giảng viên</a>
                            @endif
                        @endauth
                        <button class="btn btn-outline-secondary ms-2">Áp dụng</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-dark ms-2">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-chalkboard-teacher"></i> Danh Sách Giảng Viên</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Mã giảng viên</th>
                    <th>Họ tên</th>
                    <th>Phone</th>
                    <th>Trình độ</th>
                    <th>Quốc tịch</th>
                    <th>Bộ môn</th>
                    <th>Lớp</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $i => $teacher)
                <tr>
                    <td>{{ $teacher->id }}</td>
                    <td>
                        @if($teacher->teacher_code)
                            <strong class="text-primary">{{ $teacher->teacher_code }}</strong>
                        @else
                            <span class="text-muted">GV{{ str_pad($teacher->id, 3, '0', STR_PAD_LEFT) }}</span>
                        @endif
                    </td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->phone ?? 'Chưa có' }}</td>
                    <td>{{ $teacher->qualification ?? 'Chưa có' }}</td>
                    <td>{{ $teacher->nationality ?? 'Việt Nam' }}</td>
                    <td>{{ $teacher->department ?? 'Chưa phân' }}</td>
                    <td>{{ $teacher->class_assigned ?? 'Chưa có' }}</td>
                    <td>
                        @auth
                            @if(Auth::user()->hasPermission('edit-teachers'))
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            @endif
                            @if(Auth::user()->hasPermission('delete-teachers'))
                                <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa giảng viên này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không tìm thấy giảng viên nào. Vui lòng thêm giảng viên mới.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection



