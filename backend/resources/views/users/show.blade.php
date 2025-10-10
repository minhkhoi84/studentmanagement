@extends('layout')

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/students.css') }}">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle"></i> User Profile Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users List
                        </a>
                        @if(Auth::user()->role === 'super_admin' || (Auth::user()->role === 'admin' && $user->role !== 'super_admin'))
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit User
                        </a>
                        @endif
                    </div>

                    <div class="row">
                        <!-- User Avatar -->
                        <div class="col-md-3 text-center">
                            <div class="student-avatar">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-primary">User ID: #{{ $user->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Joined: {{ $user->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Full Name</span>
                                        <div class="info-value">{{ $user->name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Email Address</span>
                                        <div class="info-value">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Phone Number</span>
                                        <div class="info-value">{{ $user->phone ?? 'Not provided' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Role</span>
                                        <div class="info-value">
                                            @php
                                                $roleColors = [
                                                    'super_admin' => 'danger',
                                                    'admin' => 'warning', 
                                                    'user' => 'success'
                                                ];
                                                $roleTexts = [
                                                    'super_admin' => 'Super Admin',
                                                    'admin' => 'Admin',
                                                    'user' => 'User'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $roleColors[$user->role] }}">
                                                {{ $roleTexts[$user->role] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Account Status</span>
                                        <div class="info-value">
                                            <span class="badge bg-success">Active</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item mb-3">
                                        <span class="info-label">Last Updated</span>
                                        <div class="info-value">{{ $user->updated_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection










