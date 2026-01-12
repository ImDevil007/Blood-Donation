@extends('backend.layouts.master')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.donor.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Dashboard Content -->
    <div class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Error!</h5>
                    {{ session('error') }}
                </div>
            @endif

            <div class="row">
                <!-- Total Donations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_donations'] }}</h3>
                            <p>Total Donations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <a href="{{ route('backend.donor.history') }}" class="small-box-footer">
                            View History <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Successful Donations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['successful_donations'] }}</h3>
                            <p>Successful Donations</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{ route('backend.donor.history') }}" class="small-box-footer">
                            View History <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- This Year Donations -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['this_year_donations'] }}</h3>
                            <p>Donations This Year</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <a href="{{ route('backend.donor.history') }}" class="small-box-footer">
                            View History <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Eligibility Status -->
                <div class="col-lg-3 col-6">
                    <div class="small-box {{ $stats['is_eligible'] ? 'bg-success' : 'bg-danger' }}">
                        <div class="inner">
                            <h3>{{ $stats['is_eligible'] ? 'Eligible' : 'Not Eligible' }}</h3>
                            <p>Donation Status</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="small-box-footer">
                            Blood Group: {{ $stats['blood_group'] }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Donor Information Card -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Donor Information</h3>
                        </div>
                        <div class="card-body">
                            <dl class="row">
                                <dt class="col-sm-4">Donor ID:</dt>
                                <dd class="col-sm-8">{{ $donor->donor_id }}</dd>

                                <dt class="col-sm-4">Name:</dt>
                                <dd class="col-sm-8">{{ $donor->full_name }}</dd>

                                <dt class="col-sm-4">Blood Group:</dt>
                                <dd class="col-sm-8">{{ $stats['blood_group'] }}</dd>

                                <dt class="col-sm-4">Eligibility:</dt>
                                <dd class="col-sm-8">
                                    @if($stats['is_eligible'])
                                        <span class="badge badge-success">Eligible</span>
                                    @else
                                        <span class="badge badge-danger">Not Eligible</span>
                                    @endif
                                </dd>

                                @if($stats['last_donation_date'])
                                    <dt class="col-sm-4">Last Donation:</dt>
                                    <dd class="col-sm-8">{{ $stats['last_donation_date']->format('M d, Y') }}</dd>
                                @endif

                                @if($stats['next_eligible_date'] && $stats['next_eligible_date']->isFuture())
                                    <dt class="col-sm-4">Next Eligible:</dt>
                                    <dd class="col-sm-8">{{ $stats['next_eligible_date']->format('M d, Y') }}</dd>
                                @endif

                                @if($stats['eligibility_reason'])
                                    <dt class="col-sm-4">Eligibility Note:</dt>
                                    <dd class="col-sm-8">{{ $stats['eligibility_reason'] }}</dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Recent Donations -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Recent Donations</h3>
                        </div>
                        <div class="card-body">
                            @if($recentDonations->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Volume</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentDonations as $donation)
                                                <tr>
                                                    <td>{{ $donation->donation_date->format('M d, Y') }}</td>
                                                    <td>{{ $donation->blood_volume ?? '-' }} ml</td>
                                                    <td>
                                                        @if($donation->donation_status == 'successful')
                                                            <span class="badge badge-success">Successful</span>
                                                        @elseif($donation->donation_status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <a href="{{ route('backend.donor.history') }}" class="btn btn-primary btn-sm mt-2">
                                    View All History
                                </a>
                            @else
                                <p class="text-muted">No donation history found.</p>
                                <p class="text-muted">Start your donation journey today!</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
