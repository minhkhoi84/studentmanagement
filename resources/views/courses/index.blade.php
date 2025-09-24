@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Courses</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('courses.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Name, code, description">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('courses.create') }}" class="btn btn-primary">Add Course</a>
                        <button class="btn btn-outline-secondary ms-2">Apply</button>
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-dark ms-2">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Credits</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $i => $course)
                <tr>
                    <td>{{ $courses->firstItem() + $i }}</td>
                    <td><strong>{{ $course->code }}</strong></td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->credits }}</td>
                    <td>
                        <span class="badge bg-{{ $course->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($course->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('courses.edit', $course->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('courses.destroy', $course->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this course?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No courses found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $courses->links() }}
        </div>
    </div>
</div>
@endsection

