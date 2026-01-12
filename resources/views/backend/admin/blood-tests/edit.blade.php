@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Blood Test</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-tests.index') }}">Blood Tests</a>
                        </li>
                        <li class="breadcrumb-item active">Edit Blood Test</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Test Information</h3>
                        </div>

                        <form action="{{ route('backend.admin.blood-tests.update', $bloodTest->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="blood_unit_id">Blood Unit</label>
                                            <input type="text" class="form-control"
                                                value="{{ $bloodTest->bloodUnit?->unit_id }} - {{ $bloodTest->bloodUnit?->donor->name }}"
                                                readonly>
                                            <small class="text-muted">Blood unit cannot be changed after test
                                                creation</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="technician_id">Technician</label>
                                            <select name="technician_id" id="technician_id"
                                                class="form-control @error('technician_id') is-invalid @enderror">
                                                <option value="">Select Technician</option>
                                                @foreach ($technicians as $technician)
                                                    <option value="{{ $technician->id }}"
                                                        {{ old('technician_id', $bloodTest->technician_id) == $technician->id ? 'selected' : '' }}>
                                                        {{ $technician->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('technician_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="test_date">Test Date <span class="text-danger">*</span></label>
                                            <input type="date" name="test_date" id="test_date"
                                                class="form-control @error('test_date') is-invalid @enderror"
                                                value="{{ old('test_date', $bloodTest->test_date->format('Y-m-d')) }}"
                                                max="{{ date('Y-m-d') }}" required>
                                            @error('test_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="lab_reference">Lab Reference</label>
                                            <input type="text" name="lab_reference" id="lab_reference"
                                                class="form-control @error('lab_reference') is-invalid @enderror"
                                                value="{{ old('lab_reference', $bloodTest->lab_reference) }}"
                                                placeholder="Lab reference number">
                                            @error('lab_reference')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <h5>Test Results <span class="text-danger">*</span></h5>
                                        <p class="text-muted">Select the result for each test. All tests are required.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hiv_result">HIV <span class="text-danger">*</span></label>
                                            <select name="hiv_result" id="hiv_result"
                                                class="form-control @error('hiv_result') is-invalid @enderror" required>
                                                <option value="">Select Result</option>
                                                <option value="negative"
                                                    {{ old('hiv_result', $bloodTest->hiv_result) == 'negative' ? 'selected' : '' }}>
                                                    Negative</option>
                                                <option value="positive"
                                                    {{ old('hiv_result', $bloodTest->hiv_result) == 'positive' ? 'selected' : '' }}>
                                                    Positive</option>
                                                <option value="pending"
                                                    {{ old('hiv_result', $bloodTest->hiv_result) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                            @error('hiv_result')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hepatitis_b_result">Hepatitis B <span
                                                    class="text-danger">*</span></label>
                                            <select name="hepatitis_b_result" id="hepatitis_b_result"
                                                class="form-control @error('hepatitis_b_result') is-invalid @enderror"
                                                required>
                                                <option value="">Select Result</option>
                                                <option value="negative"
                                                    {{ old('hepatitis_b_result', $bloodTest->hepatitis_b_result) == 'negative' ? 'selected' : '' }}>
                                                    Negative</option>
                                                <option value="positive"
                                                    {{ old('hepatitis_b_result', $bloodTest->hepatitis_b_result) == 'positive' ? 'selected' : '' }}>
                                                    Positive</option>
                                                <option value="pending"
                                                    {{ old('hepatitis_b_result', $bloodTest->hepatitis_b_result) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                            @error('hepatitis_b_result')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="hepatitis_c_result">Hepatitis C <span
                                                    class="text-danger">*</span></label>
                                            <select name="hepatitis_c_result" id="hepatitis_c_result"
                                                class="form-control @error('hepatitis_c_result') is-invalid @enderror"
                                                required>
                                                <option value="">Select Result</option>
                                                <option value="negative"
                                                    {{ old('hepatitis_c_result', $bloodTest->hepatitis_c_result) == 'negative' ? 'selected' : '' }}>
                                                    Negative</option>
                                                <option value="positive"
                                                    {{ old('hepatitis_c_result', $bloodTest->hepatitis_c_result) == 'positive' ? 'selected' : '' }}>
                                                    Positive</option>
                                                <option value="pending"
                                                    {{ old('hepatitis_c_result', $bloodTest->hepatitis_c_result) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                            @error('hepatitis_c_result')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="syphilis_result">Syphilis <span
                                                    class="text-danger">*</span></label>
                                            <select name="syphilis_result" id="syphilis_result"
                                                class="form-control @error('syphilis_result') is-invalid @enderror"
                                                required>
                                                <option value="">Select Result</option>
                                                <option value="negative"
                                                    {{ old('syphilis_result', $bloodTest->syphilis_result) == 'negative' ? 'selected' : '' }}>
                                                    Negative</option>
                                                <option value="positive"
                                                    {{ old('syphilis_result', $bloodTest->syphilis_result) == 'positive' ? 'selected' : '' }}>
                                                    Positive</option>
                                                <option value="pending"
                                                    {{ old('syphilis_result', $bloodTest->syphilis_result) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                            @error('syphilis_result')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="malaria_result">Malaria <span class="text-danger">*</span></label>
                                            <select name="malaria_result" id="malaria_result"
                                                class="form-control @error('malaria_result') is-invalid @enderror"
                                                required>
                                                <option value="">Select Result</option>
                                                <option value="negative"
                                                    {{ old('malaria_result', $bloodTest->malaria_result) == 'negative' ? 'selected' : '' }}>
                                                    Negative</option>
                                                <option value="positive"
                                                    {{ old('malaria_result', $bloodTest->malaria_result) == 'positive' ? 'selected' : '' }}>
                                                    Positive</option>
                                                <option value="pending"
                                                    {{ old('malaria_result', $bloodTest->malaria_result) == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                            </select>
                                            @error('malaria_result')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="blood_group">Blood Group</label>
                                            <select name="blood_group" id="blood_group"
                                                class="form-control @error('blood_group') is-invalid @enderror" required>
                                                <option value="">Select Blood Group</option>
                                                @foreach ($bloodGroups as $bloodGroup)
                                                    <option value="{{ $bloodGroup->id }}"
                                                        {{ old('blood_group', $bloodTest->bloodUnit?->blood_group) == $bloodGroup->id ? 'selected' : '' }}>
                                                        {{ $bloodGroup->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('blood_group')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="overall_status">Overall Status <span
                                                class="text-danger">*</span></label>
                                        <select name="overall_status" id="overall_status"
                                            class="form-control @error('overall_status') is-invalid @enderror" required>
                                            <option value="">Select Status</option>
                                            <option value="pending"
                                                {{ old('overall_status', $bloodTest->overall_status) == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="passed"
                                                {{ old('overall_status', $bloodTest->overall_status) == 'passed' ? 'selected' : '' }}>
                                                Passed</option>
                                            <option value="failed"
                                                {{ old('overall_status', $bloodTest->overall_status) == 'failed' ? 'selected' : '' }}>
                                                Failed</option>
                                            <option value="quarantined"
                                                {{ old('overall_status', $bloodTest->overall_status) == 'quarantined' ? 'selected' : '' }}>
                                                Quarantined</option>
                                        </select>
                                        @error('overall_status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="test_notes">Test Notes</label>
                                            <textarea name="test_notes" id="test_notes" rows="3"
                                                class="form-control @error('test_notes') is-invalid @enderror"
                                                placeholder="Additional notes about the test results...">{{ old('test_notes', $bloodTest->test_notes) }}</textarea>
                                            @error('test_notes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Test
                                </button>
                                <a href="{{ route('backend.admin.blood-tests.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Tests
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
