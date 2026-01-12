@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Donor Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Donor Details</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Donor Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <div class="donor-avatar" style="width: 100px; height: 100px; background-color: #007bff; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: white; font-size: 2rem;">
                                    {{ strtoupper(substr($donor->first_name, 0, 1) . substr($donor->last_name, 0, 1)) }}
                                </div>
                                <h4 class="mt-2">{{ $donor->full_name }}</h4>
                                <p class="text-muted">{{ $donor->donor_id }}</p>
                            </div>

                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $donor->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $donor->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Blood Group:</strong></td>
                                    <td>{{ $donor->userBloodGroup?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Age:</strong></td>
                                    <td>{{ $donor->age }} years</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender:</strong></td>
                                    <td>{{ $donor->userGender?->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Weight:</strong></td>
                                    <td>{{ $donor->weight }} kg</td>
                                </tr>
                                <tr>
                                    <td><strong>Height:</strong></td>
                                    <td>{{ $donor->height ?? '-' }} cm</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($donor->is_eligible)
                                            <span class="badge badge-success">Eligible</span>
                                        @else
                                            <span class="badge badge-danger">Ineligible</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total Donations:</strong></td>
                                    <td>{{ $donor->total_donations }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Donation:</strong></td>
                                    <td>{{ $donor->last_donation_date ? $donor->last_donation_date->format('M d, Y') : 'Never' }}</td>
                                </tr>
                            </table>

                            <div class="mt-3">
                                <a href="{{ route('backend.admin.donors.edit', $donor->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.donors.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Emergency Contact</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $donor->emergency_contact_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone:</strong></td>
                                    <td>{{ $donor->emergency_contact_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Relationship:</strong></td>
                                    <td>{{ $donor->emergency_contact_relation }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-success">
                        <div class="card-header">
                            <h3 class="card-title">Address & Medical Information</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Address</h5>
                                    <p>{{ $donor->address ?? 'Not provided' }}</p>
                                    <p>{{ $donor->city ?? '' }} {{ $donor->district ? ', ' . $donor->district : '' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h5>Medical Information</h5>
                                    <p><strong>Medical History:</strong><br>{{ $donor->medical_history ?? 'None' }}</p>
                                    <p><strong>Allergies:</strong><br>{{ $donor->allergies ?? 'None' }}</p>
                                    <p><strong>Medications:</strong><br>{{ $donor->medications ?? 'None' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Donation History</h3>
                        </div>
                        <div class="card-body">
                            @if($donor->donationHistories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Volume</th>
                                                <th>Status</th>
                                                <th>Location</th>
                                                <th>Technician</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($donor->donationHistories as $history)
                                                <tr>
                                                    <td>{{ $history->donation_date->format('M d, Y') }}</td>
                                                    <td>{{ $history->blood_volume }} ml</td>
                                                    <td>
                                                        @if($history->donation_status == 'successful')
                                                            <span class="badge badge-success">Successful</span>
                                                        @elseif($history->donation_status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $history->collection_location ?? '-' }}</td>
                                                    <td>{{ $history->technician?->full_name ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No donation history found.</p>
                            @endif
                        </div>
                    </div>

                    @if($donor->eligibility_reason)
                        <div class="card card-danger">
                            <div class="card-header">
                                <h3 class="card-title">Eligibility Information</h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Reason:</strong> {{ $donor->eligibility_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
