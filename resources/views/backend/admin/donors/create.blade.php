@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Register New Donor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Register Donor</li>
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
                    <h3 class="card-title">Donor Registration Form</h3>
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

                            <form action="{{ route('backend.admin.donors.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-primary">Personal Information</h5>

                                        <div class="form-group">
                                            <label>Title</label>
                                            <select name="title" class="form-control">
                                                <option value="">Select Title</option>
                                                @foreach ($titles as $title)
                                                    <option value="{{ $title->id }}"
                                                        {{ old('title') == $title->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($title->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" name="first_name" class="form-control"
                                                value="{{ old('first_name') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="{{ old('last_name') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Phone No:</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>District</label>
                                            <input type="text" name="district" class="form-control" value="{{ old('district') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="text-primary">Medical Information</h5>

                                        <div class="form-group">
                                            <label>Blood Group</label>
                                            <select name="blood_group" class="form-control" required>
                                                <option value="">Select Blood Group</option>
                                                @foreach ($bloodGroups as $bloodGroup)
                                                    <option value="{{ $bloodGroup->id }}"
                                                        {{ old('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($bloodGroup->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">Select Gender</option>
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ old('gender') == $gender->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($gender->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob') }}"
                                                required>
                                        </div>

                                        <div class="form-group">
                                            <label>Age</label>
                                            <input type="number" name="age" id="age" class="form-control" min="18" max="65"
                                                value="{{ old('age') }}" required>
                                            <div id="age-error" class="invalid-feedback" style="display: none;"></div>
                                        </div>

                                        <div class="form-group">
                                            <label>Weight (kg)</label>
                                            <input type="number" name="weight" class="form-control" min="45" max="200" step="0.1"
                                                value="{{ old('weight') }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Height (cm)</label>
                                            <input type="number" name="height" class="form-control" min="100" max="250"
                                                value="{{ old('height') }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Medical History</label>
                                            <textarea name="medical_history" class="form-control" rows="3" placeholder="Any medical conditions, surgeries, etc.">{{ old('medical_history') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Allergies</label>
                                            <textarea name="allergies" class="form-control" rows="2" placeholder="Any known allergies">{{ old('allergies') }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Current Medications</label>
                                            <textarea name="medications" class="form-control" rows="2" placeholder="Any current medications">{{ old('medications') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5 class="text-primary">Emergency Contact Information</h5>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Emergency Contact Name</label>
                                                    <input type="text" name="emergency_contact_name" class="form-control"
                                                        value="{{ old('emergency_contact_name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Emergency Contact Phone</label>
                                                    <input type="text" name="emergency_contact_phone" class="form-control"
                                                        value="{{ old('emergency_contact_phone') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Relationship</label>
                                                    <input type="text" name="emergency_contact_relation" class="form-control"
                                                        value="{{ old('emergency_contact_relation') }}" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">Register Donor</button>
                                    <a href="{{ route('backend.admin.donors.index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
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

@section('custom-js')
<script>
    $(document).ready(function() {
        var dobInput = $('#dob');
        var ageInput = $('#age');
        var ageErrorDiv = $('#age-error');

        // Validate when user leaves the age field
        ageInput.on('blur', function() {
            var dob = dobInput.val();
            var age = ageInput.val();

            // Only validate if both fields have values
            if (!dob || !age) {
                return;
            }

            // Clear previous error
            ageErrorDiv.hide().html('');
            ageInput.removeClass('is-invalid');

            // Send AJAX request to validate
            $.ajax({
                url: '{{ route("backend.admin.donors.validate-dob") }}',
                method: 'POST',
                data: {
                    dob: dob,
                    age: age,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (!response.valid) {
                        // Show errors on age field
                        if (response.errors && response.errors.length > 0) {
                            ageErrorDiv.html(response.errors.join('<br>')).show();
                            ageInput.addClass('is-invalid');
                        }
                    } else {
                        // Remove error if valid
                        ageInput.removeClass('is-invalid');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessages = [];
                        
                        if (errors.age) {
                            errorMessages = errorMessages.concat(errors.age);
                        }
                        if (errors.dob) {
                            errorMessages = errorMessages.concat(errors.dob);
                        }

                        if (errorMessages.length > 0) {
                            ageErrorDiv.html(errorMessages.join('<br>')).show();
                            ageInput.addClass('is-invalid');
                        }
                    }
                }
            });
        });
    });
</script>
@endsection

