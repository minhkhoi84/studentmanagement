@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Thêm Khoa Mới</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('departments.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Khoa</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Khoa Công nghệ thông tin" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Khoa</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="CNTT" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trưởng Khoa</label>
                        <input type="text" name="dean" class="form-control" value="{{ old('dean') }}" placeholder="Tên trưởng khoa">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Mô tả về khoa...">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('departments.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
