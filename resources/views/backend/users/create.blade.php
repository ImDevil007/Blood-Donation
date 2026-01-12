@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">General Form</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Create User</h3>
                </div>
                <!-- /.card-header -->

                <!-- form start -->
                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            {{-- Validation Errors --}}
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

                            <form action="{{ route('backend.admin.users.store') }}" method="POST">
                                @csrf

                                <div class="form-group"><label>Title</label>
                                    <select name="title" class="form-control" required>
                                        @foreach ($titles as $title)
                                            <option class="title" value="{{ $title->id }}"
                                                {{ old('title') == $title->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($title->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ old('first_name') }}" required>
                                </div>

                                <div class="form-group"><label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ old('last_name') }}" required>
                                </div>

                                <div class="form-group"><label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>
                                </div>

                                <div class="form-group"><label>Phone No:</label>
                                    <input type="number" name="phone" class="form-control" value="{{ old('phone') }}"
                                        required>
                                </div>

                                <div class="form-group"><label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="form-group"><label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <div class="form-group"><label>Blood Group</label>
                                    <select name="blood_group" class="form-control" required>
                                        @foreach ($bloodGroups as $bloodGroup)
                                            <option class="blood_group" value="{{ $bloodGroup->id }}"
                                                {{ old('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($bloodGroup->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        @foreach ($genders as $gender)
                                            <option class="gender" value="{{ $gender->id }}"
                                                {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($gender->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>Date of Birth</label>
                                    <input type="date" name="dob" class="form-control" value="{{ old('dob') }}"
                                        required>
                                </div>

                                <div class="form-group"><label>Age</label>
                                    <input type="number" name="age" class="form-control" min="0"
                                        value="{{ old('age') }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Assign Roles</label>
                                    @foreach ($roles as $role)
                                        <div class="form-check">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                class="form-check-input"
                                                {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-success">Create</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
