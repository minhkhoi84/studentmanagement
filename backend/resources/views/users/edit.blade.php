@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Chỉnh Sửa Thành Viên</h3>
    <div class="card">
        <div class="card-body">
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Đang sửa:</strong> {{ $user->name }} ({{ $user->email }})
            </div>

            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mật khẩu mới</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Để trống nếu không đổi">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Để trống nếu không đổi">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            @if(Auth::user()->role === 'super_admin')
                                <option value="super_admin" {{ old('role', $user->role)=='super_admin'?'selected':'' }}>Super Admin</option>
                            @endif
                            <option value="user" {{ old('role', $user->role)=='user'?'selected':'' }}>User</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Phân quyền chi tiết -->
                @if($user->role === 'user')
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-key"></i> Phân Quyền Chi Tiết</h5>
                            </div>
                            <div class="card-body">
                                @php
                                    $permissions = \App\Models\Permission::orderBy('group')->orderBy('display_name')->get();
                                    $groupedPermissions = $permissions->groupBy('group');
                                    $userPermissions = $user->permissions->pluck('id')->toArray();
                                @endphp
                                
                                @foreach($groupedPermissions as $group => $permissions)
                                    <div class="mb-4 p-3 border rounded">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-primary mb-0">
                                                @switch($group)
                                                    @case('he-thong')
                                                        HỆ THỐNG
                                                        @break
                                                    @case('quan-ly-sinh-vien')
                                                        QUẢN LÝ SINH VIÊN
                                                        @break
                                                    @case('quan-ly-khoa')
                                                        QUẢN LÝ KHOA
                                                        @break
                                                    @case('quan-ly-lop')
                                                        QUẢN LÝ LỚP
                                                        @break
                                                    @case('quan-ly-thanh-vien')
                                                        QUẢN LÝ THÀNH VIÊN
                                                        @break
                                                    @case('quan-ly-vai-tro')
                                                        QUẢN LÝ VAI TRÒ
                                                        @break
                                                    @case('quan-ly-mon-hoc')
                                                        QUẢN LÝ MÔN HỌC
                                                        @break
                                                    @default
                                                        {{ strtoupper($group) }}
                                                @endswitch
                                            </h6>
                                            <div>
                                                <button type="button" class="btn btn-success btn-sm me-2" 
                                                        onclick="selectAllInGroup('{{ str_replace('-', '_', $group) }}_{{ $user->id }}')">
                                                    Chọn tất cả
                                                </button>
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                        onclick="deselectAllInGroup('{{ str_replace('-', '_', $group) }}_{{ $user->id }}')">
                                                    Hủy tất cả
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row">
                                            @foreach($permissions as $permission)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input group-{{ str_replace('-', '_', $group) }}_{{ $user->id }}" type="checkbox" 
                                                               name="permissions[]" 
                                                               value="{{ $permission->id }}"
                                                               id="perm{{ $user->id }}_{{ $permission->id }}"
                                                               {{ in_array($permission->id, $userPermissions) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="perm{{ $user->id }}_{{ $permission->id }}">
                                                            {{ $permission->display_name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>

<script>
function selectAllInGroup(groupClass) {
    console.log('Selecting all in group:', groupClass);
    
    // Thử nhiều cách tìm kiếm
    let checkboxes = document.querySelectorAll('.' + groupClass);
    console.log('Method 1 - Found checkboxes:', checkboxes.length);
    
    if (checkboxes.length === 0) {
        // Thử tìm với dấu gạch ngang
        const altClass = groupClass.replace(/_/g, '-');
        checkboxes = document.querySelectorAll('.' + altClass);
        console.log('Method 2 - Found checkboxes with dashes:', checkboxes.length);
    }
    
    if (checkboxes.length === 0) {
        // Thử tìm tất cả input có class chứa group
        checkboxes = document.querySelectorAll('input[class*="group-' + groupClass.split('_')[0] + '"]');
        console.log('Method 3 - Found checkboxes with partial match:', checkboxes.length);
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
        console.log('Checked:', checkbox.className);
    });
}

function deselectAllInGroup(groupClass) {
    console.log('Deselecting all in group:', groupClass);
    
    // Thử nhiều cách tìm kiếm
    let checkboxes = document.querySelectorAll('.' + groupClass);
    console.log('Method 1 - Found checkboxes:', checkboxes.length);
    
    if (checkboxes.length === 0) {
        // Thử tìm với dấu gạch ngang
        const altClass = groupClass.replace(/_/g, '-');
        checkboxes = document.querySelectorAll('.' + altClass);
        console.log('Method 2 - Found checkboxes with dashes:', checkboxes.length);
    }
    
    if (checkboxes.length === 0) {
        // Thử tìm tất cả input có class chứa group
        checkboxes = document.querySelectorAll('input[class*="group-' + groupClass.split('_')[0] + '"]');
        console.log('Method 3 - Found checkboxes with partial match:', checkboxes.length);
    }
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
        console.log('Unchecked:', checkbox.className);
    });
}

// Debug: Log all group classes on page load
document.addEventListener('DOMContentLoaded', function() {
    const allInputs = document.querySelectorAll('input[class*="group-"]');
    console.log('All group inputs found:', allInputs.length);
    allInputs.forEach(input => {
        console.log('Input class:', input.className);
    });
});
</script>
@endsection
