@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Danh Sách Vai Trò Người Dùng</h3>

    @auth
        @if(Auth::user()->role === 'super_admin')
            <div class="mb-3">
                <a href="{{ route('users.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm mới
                </a>
            </div>
        @endif
    @endauth

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Tên vai trò</th>
                    <th>Danh sách quyền</th>
                    <th>Mô tả</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        @php
                            $roleColors = [
                                'super_admin' => 'primary',
                                'user' => 'success'
                            ];
                            $roleTexts = [
                                'super_admin' => 'Super Admin',
                                'user' => 'User'
                            ];
                        @endphp
                        <span class="badge bg-{{ $roleColors[$user->role] }}">
                            {{ $roleTexts[$user->role] }}
                        </span>
                    </td>
                    <td>
                        @if($user->role === 'super_admin')
                            <span class="badge bg-danger">Quyền tối cao</span>
                        @else
                            @forelse($user->permissions->groupBy('group') as $group => $perms)
                                <div class="mb-1">
                                    @foreach($perms as $perm)
                                        <span class="badge bg-success me-1">{{ $perm->display_name }}</span>
                                    @endforeach
                                </div>
                            @empty
                                <span class="text-muted">Chưa có quyền</span>
                            @endforelse
                        @endif
                    </td>
                    <td>
                        @if($user->role === 'super_admin')
                            Quyền cao nhất trong hệ thống
                        @else
                            Quyền cơ bản của người dùng
                        @endif
                    </td>
                    <td>
                        @auth
                            @if(Auth::user()->role === 'super_admin')
                                <button class="btn btn-sm btn-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#permissionModal"
                                        data-user-id="{{ $user->id }}"
                                        data-user-name="{{ $user->name }}"
                                        data-user-role="{{ $user->role }}"
                                        onclick="openPermissionModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                    <i class="fas fa-key"></i> Phân quyền
                                </button>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @else
                                <span class="text-muted">Không có quyền</span>
                            @endif
                        @endauth
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Permission Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="permissionModalLabel">Phân quyền</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" id="permissionForm">
                @csrf
                @method('PUT')
                <div class="modal-body" id="permissionModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu quyền</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function selectAllInGroup(groupClass) {
    const checkboxes = document.querySelectorAll('.' + groupClass);
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllInGroup(groupClass) {
    const checkboxes = document.querySelectorAll('.' + groupClass);
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

function openPermissionModal(userId, userName, userRole) {
    // Đảm bảo Bootstrap được load
    if (typeof bootstrap === 'undefined') {
        console.error('Bootstrap is not loaded');
        return;
    }
    
    // Cập nhật form action
    const form = document.getElementById('permissionForm');
    form.action = '/roles/' + userId;
    
    // Cập nhật modal title
    const modalTitle = document.getElementById('permissionModalLabel');
    modalTitle.textContent = 'Phân quyền cho ' + userName;
    
    // Load permissions data
    loadUserPermissions(userId, userRole);
    
    // Hiển thị modal
    const modalElement = document.getElementById('permissionModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    } else {
        console.error('Modal element not found: permissionModal');
    }
}

function loadUserPermissions(userId, userRole) {
    // Tạo nội dung modal dựa trên dữ liệu có sẵn
    const modalBody = document.getElementById('permissionModalBody');
    
    // Lấy dữ liệu từ PHP (có thể cần AJAX call thực tế)
    const userData = @json($users->keyBy('id'));
    const permissionsData = @json($groupedPermissions);
    
    if (userRole === 'super_admin') {
        modalBody.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-crown"></i> Super Admin có tất cả quyền, không cần phân quyền riêng.
            </div>
        `;
        return;
    }
    
    if (userRole === 'user') {
        modalBody.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> User chỉ có quyền xem, không thể phân quyền thêm.
            </div>
        `;
        return;
    }
    
    let content = '';
    for (const [group, permissions] of Object.entries(permissionsData)) {
        content += `
            <div class="mb-4 p-3 border rounded">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="text-primary mb-0">${group.toUpperCase()}</h6>
                    <div>
                        <button type="button" class="btn btn-success btn-sm me-2" 
                                onclick="selectAllInGroup('${group.replace(/-/g, '_')}_${userId}')">
                            Chọn tất cả
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" 
                                onclick="deselectAllInGroup('${group.replace(/-/g, '_')}_${userId}')">
                            Hủy tất cả
                        </button>
                    </div>
                </div>
                <div class="row">
        `;
        
        permissions.forEach(permission => {
            const isChecked = userData[userId] && userData[userId].permissions && 
                             userData[userId].permissions.some(p => p.id === permission.id) ? 'checked' : '';
            content += `
                <div class="col-md-6 mb-2">
                    <div class="form-check">
                        <input class="form-check-input group-${group.replace(/-/g, '_')}_${userId}" type="checkbox" 
                               name="permissions[]" 
                               value="${permission.id}"
                               id="perm${userId}_${permission.id}"
                               ${isChecked}>
                        <label class="form-check-label" for="perm${userId}_${permission.id}">
                            ${permission.display_name}
                        </label>
                    </div>
                </div>
            `;
        });
        
        content += `
                </div>
            </div>
        `;
    }
    
    modalBody.innerHTML = content;
}

// Fallback nếu Bootstrap chưa load
document.addEventListener('DOMContentLoaded', function() {
    // Kiểm tra Bootstrap
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap not loaded, trying to load...');
        // Có thể thêm script tag để load Bootstrap
    }
});
</script>
@endsection
