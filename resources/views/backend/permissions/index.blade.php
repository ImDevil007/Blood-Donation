@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Permission Management</h1>
                    <a href="{{ route('backend.admin.permissions.create') }}" class="btn btn-sm btn-primary">Add New
                        Permission</a>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Permission List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Permission Name</th>
                                <th width="150">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($permissions as $permission)
                                <tr>
                                    <td>{{ $permission->id }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>
                                        <a href="{{ route('backend.admin.permissions.edit', $permission->id) }}"
                                            class="btn btn-sm btn-info">Edit</a>
                                        <form action="{{ route('backend.admin.permissions.destroy', $permission->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete permission?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No permissions found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($permissions->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of
                                {{ $permissions->total() }} results</div>
                            <div>{{ $permissions->onEachSide(1)->links('pagination::bootstrap-4') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
