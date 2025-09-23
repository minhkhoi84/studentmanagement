@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Add Course</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('courses.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="CS101" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Credits</label>
                        <input type="number" name="credits" class="form-control" value="{{ old('credits', 3) }}" min="1" max="10" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active" {{ old('status','active')=='active'?'selected':'' }}>Active</option>
                            <option value="inactive" {{ old('status')=='inactive'?'selected':'' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                    </div>
                </div>
                <button class="btn btn-success">Save</button>
                <a href="{{ route('courses.index') }}" class="btn btn-secondary ms-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
