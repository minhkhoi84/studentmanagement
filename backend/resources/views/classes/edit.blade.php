@extends('layout')
@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-edit text-primary"></i> Chỉnh Sửa Lớp</h2>
            <p class="text-muted mb-0">Cập nhật thông tin lớp: <strong>{{ $class->name }}</strong></p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('classes.show', $class->id) }}" class="btn btn-outline-info">
                <i class="fas fa-eye"></i> Xem chi tiết
            </a>
            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-door-open"></i> Thông tin lớp học</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('classes.update', $class->id) }}" id="classForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-info-circle"></i> Thông tin cơ bản
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-door-open text-primary"></i> Tên Lớp <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $class->name) }}" placeholder="VD: CNTT01K1, KTPM01K1" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Tên đầy đủ của lớp học</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-code text-info"></i> Mã Lớp <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                                       value="{{ old('code', $class->code) }}" placeholder="VD: CNTT01, KTPM01" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Mã định danh duy nhất cho lớp</div>
                            </div>
                        </div>

                        <!-- Department and Status -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-cogs"></i> Cấu hình
                                </h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-university text-warning"></i> Khoa
                                </label>
                                <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                                    <option value="">Chọn khoa (tùy chọn)</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $class->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Khoa quản lý lớp học</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-toggle-on text-success"></i> Trạng Thái
                                </label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror">
                                    <option value="active" {{ old('status', $class->status)=='active'?'selected':'' }}>
                                        <i class="fas fa-check-circle"></i> Hoạt động
                                    </option>
                                    <option value="inactive" {{ old('status', $class->status)=='inactive'?'selected':'' }}>
                                        <i class="fas fa-times-circle"></i> Không hoạt động
                                    </option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Trạng thái hoạt động của lớp</div>
                            </div>
                        </div>

                        <!-- Class Info Summary -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-info border-bottom pb-2 mb-3">
                                    <i class="fas fa-info"></i> Thông tin bổ sung
                                </h6>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">Ngày tạo:</small>
                                    <div class="fw-bold">{{ $class->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light p-3 rounded">
                                    <small class="text-muted">Cập nhật lần cuối:</small>
                                    <div class="fw-bold">{{ $class->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('classes.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật lớp
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.getElementById('classForm');
    form.addEventListener('submit', function(e) {
        const name = document.querySelector('input[name="name"]').value.trim();
        const code = document.querySelector('input[name="code"]').value.trim();
        
        if (!name || !code) {
            e.preventDefault();
            alert('Vui lòng nhập đầy đủ tên lớp và mã lớp!');
            return false;
        }
        
        if (code.length < 3) {
            e.preventDefault();
            alert('Mã lớp phải có ít nhất 3 ký tự!');
            return false;
        }
    });
});
</script>
@endpush
@endsection
