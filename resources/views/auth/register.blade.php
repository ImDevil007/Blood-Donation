<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | Vital Blood</title>

    <link rel="stylesheet" href="{{ asset('/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <style>
        .login-box,
        .register-box {
            width: 720px !important;
        }
    </style>
</head>

<body class="hold-transition register-page">
    <div class="register-box pb-5">
        <div class="register-logo pt-5">
            <a href="#"><b>Vital</b>Blood</a>
        </div>

        <div class="card">
            <div class="card-body register-card-body p-5">
                <p class="login-box-msg">Register as a Donor</p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf
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

                    <!-- Title -->
                    <div class="form-group mb-3">
                        <select name="title" class="form-control" required>
                            <option value="">Select Title</option>
                            @foreach ($titles as $title)
                                <option value="{{ $title->id }}" {{ old('title') == $title->id ? 'selected' : '' }}>
                                    {{ ucfirst($title->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('title')
                            <p class="text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- First Name -->
                    <div class="input-group mb-3">
                        <input type="text" name="first_name" class="form-control" placeholder="First Name"
                            value="{{ old('first_name') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    @error('first_name')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Last Name -->
                    <div class="input-group mb-3">
                        <input type="text" name="last_name" class="form-control" placeholder="Last Name"
                            value="{{ old('last_name') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-user"></span></div>
                        </div>
                    </div>
                    @error('last_name')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Email -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email"
                            value="{{ old('email') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-envelope"></span></div>
                        </div>
                    </div>
                    @error('email')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Phone -->
                    <div class="input-group mb-3">
                        <input type="text" name="phone" class="form-control" placeholder="Phone Number"
                            value="{{ old('phone') }}" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-phone"></span></div>
                        </div>
                    </div>
                    @error('phone')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @error('password')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Confirm Password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password" required>
                        <div class="input-group-append">
                            <div class="input-group-text"><span class="fas fa-lock"></span></div>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <p class="text-danger text-sm">{{ $message }}</p>
                    @enderror

                    <!-- Blood Group -->
                    <div class="form-group mb-3">
                        <select name="blood_group" class="form-control" required>
                            <option value="">Select Blood Group</option>
                            @foreach ($bloodGroups as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}"
                                    {{ old('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                    {{ ucfirst($bloodGroup->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group')
                            <p class="text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="form-group mb-3">
                        <select name="gender" class="form-control" required>
                            <option value="">Select Gender</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}"
                                    {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                    {{ ucfirst($gender->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <p class="text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- DOB -->
                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-md-6 px-2">Date of Birth</div>
                            <div class="col-md-6">
                                <input type="date" name="dob" class="form-control"
                                    value="{{ old('dob') }}" required>
                                @error('dob')
                                    <p class="text-danger text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Age -->
                    <div class="form-group mb-3">
                        <input type="number" name="age" class="form-control" placeholder="Age" min="0"
                            value="{{ old('age') }}" required>
                        @error('age')
                            <p class="text-danger text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">Register</button>
                        </div>
                    </div>
                </form>

                <p class="mt-3 mb-0 text-center">
                    <a href="{{ route('login') }}">Already registered?</a>
                </p>
            </div>
        </div>
    </div>

    <script src="{{ asset('/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/dist/js/adminlte.min.js') }}"></script>
</body>

</html>
