@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Chỉnh Sửa Khoa</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('departments.update', $department->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Khoa</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $department->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Khoa</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $department->code) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trưởng Khoa</label>
                        <input type="text" name="dean" class="form-control" value="{{ old('dean', $department->dean) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $department->status)=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $department->status)=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" rows="4">{{ old('description', $department->description) }}</textarea>
                    </div>
                </div>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
