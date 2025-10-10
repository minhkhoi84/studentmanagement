@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Add Teacher</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Họ tên</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Nguyễn Văn A" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Mã giảng viên</label>
                        <input type="text" name="teacher_code" class="form-control" value="{{ old('teacher_code') }}" placeholder="GV001" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="teacher@example.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="0123456789">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Trình độ</label>
                        <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" placeholder="Thạc sĩ">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Quốc tịch</label>
                        <input type="text" name="nationality" class="form-control" value="{{ old('nationality', 'Việt Nam') }}" placeholder="Việt Nam">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Bộ môn</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department') }}" placeholder="Khoa công nghệ thông tin">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Lớp phụ trách</label>
                        <input type="text" name="class_assigned" class="form-control" value="{{ old('class_assigned') }}" placeholder="CNTT01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Hoạt động</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-success">Save</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection



