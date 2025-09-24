@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Chỉnh Sửa Lớp</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('classes.update', $class->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Lớp</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $class->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Lớp</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $class->code) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Khoa</label>
                        <select name="department_id" class="form-select">
                            <option value="">Chọn khoa</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $class->department_id) == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $class->status)=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $class->status)=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
