@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Quản Lý Môn Học</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên môn, mã môn, mô tả">
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
                            @if(Auth::user()->hasPermission('create-courses'))
                                <a href="{{ route('courses.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm môn học
                                </a>
                            @endif
                        @endauth
                        <button class="btn btn-primary ms-2"><i class="fas fa-filter"></i> Áp dụng</button>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary ms-2">Đặt lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <h5 class="mb-3"><i class="fas fa-book-open"></i> Danh Sách Môn Học</h5>
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Mã Môn</th>
                    <th>Tên Môn Học</th>
                    <th>Số Tín Chỉ</th>
                    <th>Trạng Thái</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $i => $course)
                <tr>
                    <td>{{ $courses->firstItem() + $i }}</td>
                    <td><strong class="text-primary">{{ $course->code }}</strong></td>
                    <td>{{ $course->name }}</td>
                    <td><span class="badge bg-info">{{ $course->credits }} TC</span></td>
                    <td>
                        <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">
                            {{ $course->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                        </span>
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->hasPermission('edit-courses'))
                                <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                            @endif
                            @if(Auth::user()->hasPermission('delete-courses'))
                                <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa môn học này không?')">
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
                    <td colspan="6" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không tìm thấy môn học nào. Vui lòng thêm môn học mới.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection

