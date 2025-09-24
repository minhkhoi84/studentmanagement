@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Teachers</h3>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('teachers.index') }}">
                <div class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Name, email, phone">
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
                        <a href="{{ route('teachers.create') }}" class="btn btn-primary">Add Teacher</a>
                        <button class="btn btn-outline-secondary ms-2">Apply</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-dark ms-2">Reset</a>
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
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $i => $teacher)
                <tr>
                    <td>{{ $teachers->firstItem() + $i }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone }}</td>
                    <td>{{ $teacher->department }}</td>
                    <td>
                        <span class="badge bg-{{ $teacher->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($teacher->status) }}</span>
                    </td>
                    <td>
                        <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this teacher?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">No teachers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>
            {{ $teachers->links() }}
        </div>
    </div>
</div>
@endsection



