@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Edit User</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="container">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('backend.admin.users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group"><label>Title</label>
                                    <select name="title" class="form-control" required>
                                        @foreach ($titles as $title)
                                            <option value="{{ $title->id }}"
                                                {{ old('title', $user->title) == $title->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($title->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>First Name</label>
                                    <input type="text" name="first_name" class="form-control"
                                        value="{{ old('first_name', $user->first_name) }}" required>
                                </div>

                                <div class="form-group"><label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control"
                                        value="{{ old('last_name', $user->last_name) }}" required>
                                </div>

                                <div class="form-group"><label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="form-group"><label>Phone No:</label>
                                    <input type="number" name="phone" class="form-control"
                                        value="{{ old('phone', $user->phone) }}" required>
                                </div>

                                <div class="form-group"><label>Blood Group</label>
                                    <select name="blood_group" class="form-control" required>
                                        @foreach ($bloodGroups as $bloodGroup)
                                            <option value="{{ $bloodGroup->id }}"
                                                {{ old('blood_group', $user->blood_group) == $bloodGroup->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($bloodGroup->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>Gender</label>
                                    <select name="gender" class="form-control" required>
                                        @foreach ($genders as $gender)
                                            <option value="{{ $gender->id }}"
                                                {{ old('gender', $user->gender) == $gender->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($gender->name)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group"><label>Date of Birth</label>
                                    <input type="date" name="dob" class="form-control"
                                        value="{{ old('dob', $user->dob) }}" required>
                                </div>

                                <div class="form-group"><label>Age</label>
                                    <input type="number" name="age" class="form-control" min="0"
                                        value="{{ old('age', $user->age) }}" required>
                                </div>

                                <div class="form-group">
                                    <label>Assign Roles</label>
                                    @foreach ($roles as $role)
                                        <div class="form-check">
                                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                                class="form-check-input"
                                                {{ (is_array(old('roles', $userRoles)) && in_array($role->name, old('roles', $userRoles))) ? 'checked' : '' }}>
                                            <label class="form-check-label">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
