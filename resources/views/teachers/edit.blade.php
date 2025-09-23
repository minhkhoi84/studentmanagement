@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Edit Teacher</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $teacher->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $teacher->email) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $teacher->phone) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Department</label>
                        <input type="text" name="department" class="form-control" value="{{ old('department', $teacher->department) }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status', $teacher->status)=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status', $teacher->status)=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection


