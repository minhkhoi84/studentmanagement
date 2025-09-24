@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Thêm Lớp Mới</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('classes.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên Lớp</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="CNTT01K1" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã Lớp</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="CNTT01" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Khoa</label>
                        <select name="department_id" class="form-select">
                            <option value="">Chọn khoa</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng Thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu
                </button>
                <a href="{{ route('classes.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
