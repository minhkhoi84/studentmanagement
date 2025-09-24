@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Quản Lý Lớp</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên lớp, mã lớp, GVCN">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Khoa</label>
                        <select name="department_id" class="form-select">
                            <option value="">Tất cả khoa</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('classes.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm lớp
                                </a>
                            @endif
                        @endauth
                        <button class="btn btn-primary ms-2"><i class="fas fa-filter"></i> Áp dụng</button>
                        <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary ms-2">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-door-open"></i> Danh Sách Lớp</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mã Lớp</th>
                    <th>Tên Lớp</th>
                    <th>Khoa</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classes as $i => $class)
                <tr>
                    <td>{{ $classes->firstItem() + $i }}</td>
                    <td><strong class="text-primary">{{ $class->code }}</strong></td>
                    <td>{{ $class->name }}</td>
                    <td>{{ $class->department ? $class->department->name : 'Chưa phân' }}</td>
                    <td>
                        <span class="badge bg-{{ $class->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $class->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa lớp này không?')">
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
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không tìm thấy lớp nào. Vui lòng thêm lớp mới.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $classes->links() }}
        </div>
    </div>
</div>
@endsection
