@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Danh Sách Thành Viên</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label">Tên tài khoản</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên hoặc email">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ request('email') }}" placeholder="Email">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Vai trò</label>
                        <select name="role" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="super_admin" {{ request('role')=='super_admin' ? 'selected' : '' }}>Super Admin</option>
                            <option value="admin" {{ request('role')=='admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role')=='user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary"><i class="fas fa-search"></i> Tìm kiếm</button>
                    </div>
                    <div class="col-md-3">
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <a href="{{ route('users.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Thêm mới
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên tài khoản</th>
                    <th>Họ và tên</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><strong class="text-primary">{{ strtolower(explode('@', $user->email)[0]) }}</strong></td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? 'Chưa có' }}</td>
                    <td>
                        @php
                            $roleColors = [
                                'super_admin' => 'danger',
                                'admin' => 'warning', 
                                'user' => 'success'
                            ];
                            $roleTexts = [
                                'super_admin' => 'Super Admin',
                                'admin' => 'Admin',
                                'user' => 'User'
                            ];
                        @endphp
                        <span class="badge bg-{{ $roleColors[$user->role] }}">
                            {{ $roleTexts[$user->role] }}
                        </span>
                    </td>
                    <td>
                        <span class="badge bg-success">Hoạt động</span>
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin' && $user->role !== 'super_admin'))
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Bạn có chắc muốn xóa thành viên này không?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            @else
                                <span class="text-muted">Không có quyền</span>
                            @endif
                        @endauth
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Không tìm thấy thành viên nào.
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
