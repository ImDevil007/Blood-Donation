@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Recipient</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Recipient</li>
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
                    <h3 class="card-title">Edit Recipient Information</h3>
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

                            <form action="{{ route('backend.admin.recipients.update', $recipient->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="text-primary">Personal Information</h5>

                                        <div class="form-group">
                                            <label>Patient Code</label>
                                            <input type="text" class="form-control" value="{{ $recipient->patient_code }}" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label>Title</label>
                                            <select name="title" class="form-control">
                                                <option value="">Select Title</option>
                                                @foreach ($titles as $title)
                                                    <option value="{{ $title->id }}"
                                                        {{ old('title', $recipient->title) == $title->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($title->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input type="text" name="first_name" class="form-control"
                                                value="{{ old('first_name', $recipient->first_name) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <input type="text" name="last_name" class="form-control"
                                                value="{{ old('last_name', $recipient->last_name) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Date of Birth</label>
                                            <input type="date" name="dob" class="form-control"
                                                value="{{ old('dob', $recipient->dob?->format('Y-m-d')) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control" required>
                                                <option value="">Select Gender</option>
                                                @foreach ($genders as $gender)
                                                    <option value="{{ $gender->id }}"
                                                        {{ old('gender', $recipient->gender) == $gender->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($gender->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Blood Group</label>
                                            <select name="blood_group" class="form-control" required>
                                                <option value="">Select Blood Group</option>
                                                @foreach ($bloodGroups as $bloodGroup)
                                                    <option value="{{ $bloodGroup->id }}"
                                                        {{ old('blood_group', $recipient->blood_group) == $bloodGroup->id ? 'selected' : '' }}>
                                                        {{ ucfirst(trans($bloodGroup->name)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Contact Number</label>
                                            <input type="text" name="contact_number" class="form-control"
                                                value="{{ old('contact_number', $recipient->contact_number) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email', $recipient->email) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Address</label>
                                            <textarea name="address" class="form-control" rows="3">{{ old('address', $recipient->address) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>City</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city', $recipient->city) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>District</label>
                                            <input type="text" name="district" class="form-control" value="{{ old('district', $recipient->district) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h5 class="text-primary">Medical Information</h5>

                                        <div class="form-group">
                                            <label>Hospital Name</label>
                                            <input type="text" name="hospital_name" class="form-control"
                                                value="{{ old('hospital_name', $recipient->hospital_name) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Doctor Name</label>
                                            <input type="text" name="doctor_name" class="form-control"
                                                value="{{ old('doctor_name', $recipient->doctor_name) }}" required>
                                        </div>

                                        <div class="form-group">
                                            <label>Admission Date</label>
                                            <input type="date" name="admission_date" class="form-control"
                                                value="{{ old('admission_date', $recipient->admission_date?->format('Y-m-d')) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Blood Required Date</label>
                                            <input type="date" name="blood_required_date" class="form-control"
                                                value="{{ old('blood_required_date', $recipient->blood_required_date?->format('Y-m-d')) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Blood Quantity Required</label>
                                            <input type="number" name="blood_quantity_required" class="form-control" min="1" max="10"
                                                value="{{ old('blood_quantity_required', $recipient->blood_quantity_required) }}">
                                        </div>

                                        <div class="form-group">
                                            <label>Request Status</label>
                                            <select name="request_status" class="form-control" required>
                                                @php $statuses = ['pending', 'accepted', 'fulfilled', 'rejected']; @endphp
                                                @foreach($statuses as $status)
                                                    <option value="{{ $status }}"
                                                        {{ old('request_status', $recipient->request_status) == $status ? 'selected' : '' }}>
                                                        {{ ucfirst($status) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Diagnosis</label>
                                            <textarea name="diagnosis" class="form-control" rows="3" placeholder="Enter patient diagnosis" required>{{ old('diagnosis', $recipient->diagnosis) }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes">{{ old('notes', $recipient->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Update Recipient</button>
                                    <a href="{{ route('backend.admin.recipients.index') }}" class="btn btn-secondary">Cancel</a>
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
