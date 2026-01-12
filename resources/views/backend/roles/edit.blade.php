@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <h1>Edit Role</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit Role</h3>
                </div>

                <div class="card-body">
                    <div class="container">
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('backend.admin.roles.update', $role->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Role Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $role->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Assign Permissions</label>
                                @foreach ($permissions as $permission)
                                    <div class="form-check">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                            class="form-check-input"
                                            {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                        <label class="form-check-label">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </div>

                            <button class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
