@extends('layout')
@section('content')
    <div class="container-fluid">
        <h3 align="center" class="mt-3 mb-4">Quản Lý Sinh Viên</h3>
        
        <div class="row">
            <div class="col-md-12">
                <!-- Filters & Search -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('students.index') }}">
                        <div class="row g-2 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label"><i class="fas fa-search"></i> Tìm kiếm</label>
                                <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên, email, mã SV, điện thoại">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="male" {{ request('gender')=='male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ request('gender')=='other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="">Tất cả</option>
                                    <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Hoạt động</option>
                                    <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Không hoạt động</option>
                                    <option value="graduated" {{ request('status')=='graduated' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                                    <option value="suspended" {{ request('status')=='suspended' ? 'selected' : '' }}>Đình chỉ</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Lớp</label>
                                <input type="text" class="form-control" name="class" value="{{ request('class') }}" placeholder="CNTT01">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Ngành</label>
                                <input type="text" class="form-control" name="major" value="{{ request('major') }}" placeholder="Công nghệ thông tin">
                            </div>
                                <div class="col-12 mt-2">
                                    <button class="btn btn-primary"><i class="fas fa-filter"></i> Áp dụng</button>
                                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary ms-2">Đặt lại</a>
                                        @auth
                                            @if(Auth::user()->hasPermission('them-moi-sinh-vien'))
                                                <a href="{{ route('students.create') }}" class="btn btn-success ms-2">
                                                    <i class="fas fa-user-plus"></i> Thêm sinh viên
                                                </a>
                                            @endif
                                        @endauth
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Students List Table -->
                <div class="table-responsive">
                    <h5 class="mb-3"><i class="fas fa-list"></i> Danh Sách Sinh Viên</h5>
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fas fa-user"></i> Tên Sinh Viên</th>
                                <th scope="col"><i class="fas fa-map-marker-alt"></i> Địa Chỉ</th>
                                <th scope="col"><i class="fas fa-phone"></i> Điện Thoại</th>
                                <th scope="col"><i class="fas fa-cog"></i> Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $key => $student)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->address }}</td>
                                    <td>{{ $student->mobile }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm me-1">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        @auth
                                            @if(Auth::user()->hasPermission('chinh-sua-thong-tin-sinh-vien'))
                                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm me-1">
                                                    <i class="fas fa-edit"></i> Sửa
                                                </a>
                                            @endif
                                            @if(Auth::user()->hasPermission('xoa-sinh-vien'))
                                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa sinh viên này không?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> Không tìm thấy sinh viên nào. Vui lòng thêm sinh viên mới.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div>
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css')
    <style>
        .form-area{
            padding: 20px;
            margin-top: 20px;
            background-color:#b3e5fc;
        }

        .bi-trash-fill{
            color:red;
            font-size: 18px;
        }

        .bi-pencil{
            color:green;
            font-size: 18px;
            margin-left: 20px;
        }
    </style>
@endpush