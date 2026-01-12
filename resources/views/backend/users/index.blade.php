@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">User Management</h1>
                    <a href="{{ route('backend.admin.users.create') }}" class="btn btn-sm btn-primary">Add New User</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Users</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Filter Form --}}
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-control">
                            <option value="">All Roles</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="blood_group" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach ($bloodGroups as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}"
                                    {{ request('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                    {{ $bloodGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="gender" class="form-control">
                            <option value="">All Genders</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}"
                                    {{ request('gender') == $gender->id ? 'selected' : '' }}>
                                    {{ $gender->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-between">
                    <button class="btn btn-sm btn-secondary">Apply Filters</button>
                    <a href="{{ route('backend.admin.users.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">User List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Blood Group</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Roles</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->full_name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->userBloodGroup?->name ?? '-' }}</td>
                                    <td>{{ $user->age }}</td>
                                    <td>{{ $user->UserGender?->name ?? '-' }}</td>
                                    <td>{{ implode(', ', $user->getRoleNames()->toArray()) }}</td>
                                    <td>
                                        <a href="{{ route('backend.admin.users.edit', $user->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('backend.admin.users.destroy', $user->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete user?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($users->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                                results
                            </div>
                            <div>
                                {{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
