@extends('backend.layouts.master')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Analytics Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Analytics</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- Total Donors -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['total_donors'] }}</h3>
                            <p>Total Donors</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <a href="{{ route('backend.admin.donors.index') }}" class="small-box-footer">
                            View Donors <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Active Donors -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['active_donors'] }}</h3>
                            <p>Active Donors</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <a href="{{ route('backend.admin.donors.index') }}" class="small-box-footer">
                            View Active <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Blood Units -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $stats['total_blood_units'] }}</h3>
                            <p>Blood Units</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tint"></i>
                        </div>
                        <a href="{{ route('backend.admin.blood-units.index') }}" class="small-box-footer">
                            View Units <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Available Units -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>{{ $stats['available_units'] }}</h3>
                            <p>Available Units</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <a href="{{ route('backend.admin.blood-units.index') }}" class="small-box-footer">
                            View Available <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Recipients -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $stats['total_recipients'] }}</h3>
                            <p>Recipients</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-user-injured"></i>
                        </div>
                        <a href="{{ route('backend.admin.recipients.index') }}" class="small-box-footer">
                            View Recipients <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Collection Camps -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $stats['total_camps'] }}</h3>
                            <p>Collection Camps</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <a href="{{ route('backend.admin.blood-collection-camps.index') }}" class="small-box-footer">
                            View Camps <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Pending Tests -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $stats['pending_tests'] }}</h3>
                            <p>Pending Tests</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <a href="{{ route('backend.admin.blood-tests.index') }}" class="small-box-footer">
                            View Tests <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Approved Tests -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $stats['approved_tests'] }}</h3>
                            <p>Approved Tests</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check"></i>
                        </div>
                        <a href="{{ route('backend.admin.blood-tests.index') }}" class="small-box-footer">
                            View Approved <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
