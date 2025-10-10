@extends('layout')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-door-open text-primary"></i> Quản Lý Lớp</h2>
            <p class="text-muted mb-0">Quản lý thông tin các lớp học trong hệ thống</p>
        </div>
        @auth
            @can('create', App\Models\ClassModel::class)
                <a href="{{ route('classes.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus"></i> Thêm lớp mới
                </a>
            @endcan
        @endauth
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-filter"></i> Bộ lọc và tìm kiếm</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('classes.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold"><i class="fas fa-search text-primary"></i> Tìm kiếm</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" 
                               placeholder="Tên lớp, mã lớp, GVCN...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-university text-info"></i> Khoa</label>
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
                        <label class="form-label fw-bold"><i class="fas fa-toggle-on text-success"></i> Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh"></i> Đặt lại
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Classes List Section -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> Danh Sách Lớp ({{ $classes->total() }} lớp)</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">#</th>
                            <th class="border-0"><i class="fas fa-code text-primary"></i> Mã Lớp</th>
                            <th class="border-0"><i class="fas fa-door-open text-info"></i> Tên Lớp</th>
                            <th class="border-0"><i class="fas fa-university text-warning"></i> Khoa</th>
                            <th class="border-0"><i class="fas fa-toggle-on text-success"></i> Trạng Thái</th>
                            <th class="border-0"><i class="fas fa-cogs text-secondary"></i> Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $i => $class)
                        <tr class="align-middle">
                            <td class="fw-bold text-muted">{{ $classes->firstItem() + $i }}</td>
                            <td>
                                <span class="badge bg-primary fs-6 px-3 py-2">{{ $class->code }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-door-open text-primary"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $class->name }}</h6>
                                        <small class="text-muted">ID: #{{ $class->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($class->department)
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                        <i class="fas fa-university me-1"></i>{{ $class->department->name }}
                                    </span>
                                @else
                                    <span class="text-muted">Chưa phân khoa</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $class->status === 'active' ? 'success' : 'secondary' }} fs-6 px-3 py-2">
                                    <i class="fas fa-{{ $class->status === 'active' ? 'check-circle' : 'times-circle' }} me-1"></i>
                                    {{ $class->status === 'active' ? 'Hoạt động' : 'Không hoạt động' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('classes.show', $class->id) }}" class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @auth
                                        @can('update', $class)
                                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        @can('delete', $class)
                                            <form action="{{ route('classes.destroy', $class->id) }}" method="POST" style="display:inline" 
                                                  onsubmit="return confirm('⚠️ Bạn có chắc muốn xóa lớp \'{{ $class->name }}\' không?\n\nHành động này không thể hoàn tác!')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Xóa lớp">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-door-open fa-3x mb-3 opacity-50"></i>
                                    <h5>Không tìm thấy lớp nào</h5>
                                    <p>Hãy thử thay đổi bộ lọc hoặc thêm lớp mới vào hệ thống.</p>
                                    @auth
                                        @can('create', App\Models\ClassModel::class)
                                            <a href="{{ route('classes.create') }}" class="btn btn-success">
                                                <i class="fas fa-plus"></i> Thêm lớp đầu tiên
                                            </a>
                                        @endcan
                                    @endauth
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($classes->hasPages())
        <div class="card-footer bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted">
                    Hiển thị {{ $classes->firstItem() }} - {{ $classes->lastItem() }} trong tổng số {{ $classes->total() }} lớp
                </div>
                <div>
                    {{ $classes->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
