@extends('layout')
@section('content')
    <div class="container-fluid">
        <h3 align="center" class="mt-3 mb-4">Student Management</h3>
        
        <div class="row">
            <div class="col-md-12">
                <!-- Filters & Search -->
                <div class="card mb-3">
                    <div class="card-body">
                        <form method="GET" action="{{ route('students.index') }}">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label"><i class="fas fa-search"></i> Search</label>
                                    <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Name, email, code, mobile">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="">All</option>
                                        <option value="male" {{ request('gender')=='male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ request('gender')=='female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ request('gender')=='other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All</option>
                                        <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="graduated" {{ request('status')=='graduated' ? 'selected' : '' }}>Graduated</option>
                                        <option value="suspended" {{ request('status')=='suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Class</label>
                                    <input type="text" class="form-control" name="class" value="{{ request('class') }}" placeholder="CNTT01">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">Major</label>
                                    <input type="text" class="form-control" name="major" value="{{ request('major') }}" placeholder="Computer Science">
                                </div>
                                <div class="col-12 mt-2">
                                    <button class="btn btn-primary"><i class="fas fa-filter"></i> Apply</button>
                                    <a href="{{ route('students.index') }}" class="btn btn-outline-secondary ms-2">Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Students List Table -->
                <div class="table-responsive">
                    <h5 class="mb-3"><i class="fas fa-list"></i> Students List</h5>
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col"><i class="fas fa-user"></i> Student Name</th>
                                <th scope="col"><i class="fas fa-map-marker-alt"></i> Address</th>
                                <th scope="col"><i class="fas fa-phone"></i> Mobile</th>
                                <th scope="col"><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($students as $key => $student)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->address }}</td>
                                    <td>{{ $student->mobile }}</td>
                                    <td>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm me-1">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm me-1">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this student?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> No students found. Please add some students.
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div>
                        {{ $students->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('css')
    <style>
        .form-area{
            padding: 20px;
            margin-top: 20px;
            background-color:#b3e5fc;
        }

        .bi-trash-fill{
            color:red;
            font-size: 18px;
        }

        .bi-pencil{
            color:green;
            font-size: 18px;
            margin-left: 20px;
        }
    </style>
@endpush