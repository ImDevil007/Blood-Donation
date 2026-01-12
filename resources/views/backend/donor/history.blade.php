@extends('backend.layouts.master')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Donation History</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.donor.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Donation History</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> Success!</h5>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">My Donation History</h3>
                        </div>
                        <div class="card-body">
                            @if($donationHistories->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Volume</th>
                                                <th>Location</th>
                                                <th>Status</th>
                                                <th>Technician</th>
                                                <th>Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($donationHistories as $history)
                                                <tr>
                                                    <td>{{ $history->donation_date->format('M d, Y') }}</td>
                                                    <td>{{ $history->donationType?->value ?? '-' }}</td>
                                                    <td>{{ $history->blood_volume ?? '-' }} ml</td>
                                                    <td>{{ $history->collectionLocation?->value ?? '-' }}</td>
                                                    <td>
                                                        @if($history->donation_status == 'successful')
                                                            <span class="badge badge-success">Successful</span>
                                                        @elseif($history->donation_status == 'rejected')
                                                            <span class="badge badge-danger">Rejected</span>
                                                        @else
                                                            <span class="badge badge-warning">Pending</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $history->technician?->full_name ?? '-' }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#detailsModal{{ $history->id }}">
                                                            <i class="fas fa-eye"></i> View
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Details Modal -->
                                                <div class="modal fade" id="detailsModal{{ $history->id }}" tabindex="-1" role="dialog">
                                                    <div class="modal-dialog modal-lg" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Donation Details - {{ $history->donation_date->format('M d, Y') }}</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <dl class="row">
                                                                    <dt class="col-sm-4">Donation Date:</dt>
                                                                    <dd class="col-sm-8">{{ $history->donation_date->format('M d, Y h:i A') }}</dd>

                                                                    <dt class="col-sm-4">Donation Type:</dt>
                                                                    <dd class="col-sm-8">{{ $history->donationType?->value ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Blood Volume:</dt>
                                                                    <dd class="col-sm-8">{{ $history->blood_volume ?? '-' }} ml</dd>

                                                                    <dt class="col-sm-4">Collection Location:</dt>
                                                                    <dd class="col-sm-8">{{ $history->collectionLocation?->value ?? '-' }}</dd>

                                                                    <dt class="col-sm-4">Status:</dt>
                                                                    <dd class="col-sm-8">
                                                                        @if($history->donation_status == 'successful')
                                                                            <span class="badge badge-success">Successful</span>
                                                                        @elseif($history->donation_status == 'rejected')
                                                                            <span class="badge badge-danger">Rejected</span>
                                                                        @else
                                                                            <span class="badge badge-warning">Pending</span>
                                                                        @endif
                                                                    </dd>

                                                                    @if($history->hemoglobin_level)
                                                                        <dt class="col-sm-4">Hemoglobin Level:</dt>
                                                                        <dd class="col-sm-8">{{ $history->hemoglobin_level }} g/dL</dd>
                                                                    @endif

                                                                    @if($history->blood_pressure)
                                                                        <dt class="col-sm-4">Blood Pressure:</dt>
                                                                        <dd class="col-sm-8">{{ $history->blood_pressure }}</dd>
                                                                    @endif

                                                                    @if($history->pulse_rate)
                                                                        <dt class="col-sm-4">Pulse Rate:</dt>
                                                                        <dd class="col-sm-8">{{ $history->pulse_rate }} bpm</dd>
                                                                    @endif

                                                                    @if($history->temperature)
                                                                        <dt class="col-sm-4">Temperature:</dt>
                                                                        <dd class="col-sm-8">{{ $history->temperature }} °C</dd>
                                                                    @endif

                                                                    @if($history->weight_at_donation)
                                                                        <dt class="col-sm-4">Weight at Donation:</dt>
                                                                        <dd class="col-sm-8">{{ $history->weight_at_donation }} kg</dd>
                                                                    @endif

                                                                    @if($history->technician)
                                                                        <dt class="col-sm-4">Technician:</dt>
                                                                        <dd class="col-sm-8">{{ $history->technician->full_name }}</dd>
                                                                    @endif

                                                                    @if($history->rejection_reason)
                                                                        <dt class="col-sm-4">Rejection Reason:</dt>
                                                                        <dd class="col-sm-8">{{ $history->rejection_reason }}</dd>
                                                                    @endif

                                                                    @if($history->notes)
                                                                        <dt class="col-sm-4">Notes:</dt>
                                                                        <dd class="col-sm-8">{{ $history->notes }}</dd>
                                                                    @endif
                                                                </dl>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $donationHistories->links() }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <h5><i class="icon fas fa-info"></i> No Donation History</h5>
                                    <p>You haven't made any donations yet. Start your donation journey today!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
