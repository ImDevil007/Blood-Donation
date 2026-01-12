@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Schedule New Camp</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-collection-camps.index') }}">Collection Camps</a></li>
                        <li class="breadcrumb-item active">Schedule New Camp</li>
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
                            <h3 class="card-title">Camp Information</h3>
                        </div>

                        <form action="{{ route('backend.admin.blood-collection-camps.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Camp Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" id="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="location">Location <span class="text-danger">*</span></label>
                                            <input type="text" name="location" id="location"
                                                   class="form-control @error('location') is-invalid @enderror"
                                                   value="{{ old('location') }}" required>
                                            @error('location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" rows="3"
                                                      class="form-control @error('description') is-invalid @enderror"
                                                      placeholder="Describe the camp details...">{{ old('description') }}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" name="start_date" id="start_date"
                                                   class="form-control @error('start_date') is-invalid @enderror"
                                                   value="{{ old('start_date') }}" min="{{ date('Y-m-d') }}" required>
                                            @error('start_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_date">End Date <span class="text-danger">*</span></label>
                                            <input type="date" name="end_date" id="end_date"
                                                   class="form-control @error('end_date') is-invalid @enderror"
                                                   value="{{ old('end_date') }}" required>
                                            @error('end_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_time">Start Time <span class="text-danger">*</span></label>
                                            <input type="time" name="start_time" id="start_time"
                                                   class="form-control @error('start_time') is-invalid @enderror"
                                                   value="{{ old('start_time', '09:00') }}" required>
                                            @error('start_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="end_time">End Time <span class="text-danger">*</span></label>
                                            <input type="time" name="end_time" id="end_time"
                                                   class="form-control @error('end_time') is-invalid @enderror"
                                                   value="{{ old('end_time', '17:00') }}" required>
                                            @error('end_time')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="target_donors">Target Donors <span class="text-danger">*</span></label>
                                            <input type="number" name="target_donors" id="target_donors"
                                                   class="form-control @error('target_donors') is-invalid @enderror"
                                                   value="{{ old('target_donors') }}" min="1" max="10000" required>
                                            @error('target_donors')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="organizer_name">Organizer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="organizer_name" id="organizer_name"
                                                   class="form-control @error('organizer_name') is-invalid @enderror"
                                                   value="{{ old('organizer_name') }}" required>
                                            @error('organizer_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="organizer_contact">Organizer Contact <span class="text-danger">*</span></label>
                                            <input type="text" name="organizer_contact" id="organizer_contact"
                                                   class="form-control @error('organizer_contact') is-invalid @enderror"
                                                   value="{{ old('organizer_contact') }}" required>
                                            @error('organizer_contact')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea name="notes" id="notes" rows="3"
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="Additional notes about this camp...">{{ old('notes') }}</textarea>
                                            @error('notes')
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
                                    <i class="fas fa-save"></i> Schedule Camp
                                </button>
                                <a href="{{ route('backend.admin.blood-collection-camps.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Camps
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
