@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Chỉnh Sửa Môn Học</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.update', $course->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Môn Học</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $course->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Môn</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $course->code) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Số Tín Chỉ</label>
                        <input type="number" name="credits" class="form-control" value="{{ old('credits', $course->credits) }}" min="1" max="10" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $course->status)=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status', $course->status)=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mô Tả</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description', $course->description) }}</textarea>
                    </div>
                </div>
                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection

