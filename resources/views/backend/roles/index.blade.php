@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Role Management</h1>
                    <a href="{{ route('backend.admin.roles.create') }}" class="btn btn-sm btn-primary">Add New Role</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Role List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Role Name</th>
                                <th>Permissions</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($roles as $role)
                                <tr>
                                    <td>{{ $role->id }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ implode(', ', $role->permissions->pluck('name')->toArray()) }}</td>
                                    <td>
                                        <a href="{{ route('backend.admin.roles.edit', $role->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('backend.admin.roles.destroy', $role->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete role?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No roles found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($roles->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>Showing {{ $roles->firstItem() }} to {{ $roles->lastItem() }} of {{ $roles->total() }}
                                results</div>
                            <div>{{ $roles->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
