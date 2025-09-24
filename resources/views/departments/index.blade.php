@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Quản Lý Khoa</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('departments.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên khoa, mã khoa, trưởng khoa">
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
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('departments.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm khoa
                                </a>
                            @endif
                        @endauth
                        <button class="btn btn-primary ms-2"><i class="fas fa-filter"></i> Áp dụng</button>
                        <a href="{{ route('departments.index') }}" class="btn btn-outline-secondary ms-2">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-university"></i> Danh Sách Khoa</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mã Khoa</th>
                    <th>Tên Khoa</th>
                    <th>Trưởng Khoa</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $i => $department)
                <tr>
                    <td>{{ $departments->firstItem() + $i }}</td>
                    <td><strong class="text-primary">{{ $department->code }}</strong></td>
                    <td>{{ $department->name }}</td>
                    <td>{{ $department->dean ?? 'Chưa có' }}</td>
                    <td>
                        <span class="badge bg-{{ $department->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $department->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                                <a href="{{ route('departments.edit', $department->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            @endif
                            @if(Auth::user()->role === 'super_admin')
                                <form action="{{ route('departments.destroy', $department->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa khoa này không?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            @endif
                            @if(Auth::user()->role === 'user')
                                <span class="text-muted">Chỉ xem</span>
                            @endif
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không tìm thấy khoa nào. Vui lòng thêm khoa mới.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $departments->links() }}
        </div>
    </div>
</div>
@endsection
