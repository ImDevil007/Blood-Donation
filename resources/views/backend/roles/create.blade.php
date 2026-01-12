@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Role</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create Role</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('backend.admin.roles.store') }}" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label>Role Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>
                                </div>

                                <div class="form-group">
                                    <label>Assign Permissions</label>
                                    <div class="row">
                                        @foreach ($permissions as $index => $permission)
                                            <div class="col-md-4">
                                                <div class="form-check">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}" class="form-check-input"
                                                        {{ is_array(old('permissions')) && in_array($permission->name, old('permissions')) ? 'checked' : '' }}>
                                                    <label class="form-check-label">{{ $permission->name }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <button class="btn btn-success">Create</button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
