@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Transfer Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-transfers.index') }}">Blood Transfers</a></li>
                        <li class="breadcrumb-item active">Transfer Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-check"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-ban"></i>
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Transfer ID: {{ $bloodTransfer->transfer_id }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.blood-transfers.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Transfers
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Transfer ID:</th>
                                            <td>{{ $bloodTransfer->transfer_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Source Bank:</th>
                                            <td><strong>{{ $bloodTransfer->source_bank }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Destination Bank:</th>
                                            <td><strong>{{ $bloodTransfer->destination_bank }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Blood Group:</th>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ ucfirst(trans($bloodTransfer->bloodGroup->name ?? 'N/A')) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Blood Type:</th>
                                            <td>
                                                @if($bloodTransfer->bloodType)
                                                    <span class="badge badge-info">
                                                        {{ ucfirst(trans($bloodTransfer->bloodType->name)) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Quantity:</th>
                                            <td>
                                                <strong class="text-primary">{{ $bloodTransfer->quantity }}</strong>
                                                {{ $bloodTransfer->unit }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Storage Temperature:</th>
                                            <td>
                                                @if($bloodTransfer->temperature)
                                                    {{ $bloodTransfer->temperature }}°C
                                                @else
                                                    <span class="text-muted">Not specified</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if ($bloodTransfer->status == 'pending')
                                                    <span class="badge badge-warning">Pending</span>
                                                @elseif ($bloodTransfer->status == 'approved')
                                                    <span class="badge badge-info">Approved</span>
                                                @elseif ($bloodTransfer->status == 'completed')
                                                    <span class="badge badge-success">Completed</span>
                                                @elseif ($bloodTransfer->status == 'rejected')
                                                    <span class="badge badge-danger">Rejected</span>
                                                @else
                                                    <span class="badge badge-secondary">Cancelled</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Requested By:</th>
                                            <td>{{ $bloodTransfer->requestedBy->first_name ?? 'N/A' }} {{ $bloodTransfer->requestedBy->last_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Requested Date:</th>
                                            <td>{{ $bloodTransfer->created_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @if ($bloodTransfer->approved_by)
                                        <tr>
                                            <th>Approved/Rejected By:</th>
                                            <td>{{ $bloodTransfer->approvedBy->first_name ?? 'N/A' }} {{ $bloodTransfer->approvedBy->last_name ?? '' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Approved/Rejected Date:</th>
                                            <td>{{ $bloodTransfer->approved_at ? $bloodTransfer->approved_at->format('F d, Y \a\t g:i A') : 'N/A' }}</td>
                                        </tr>
                                        @endif
                                        @if ($bloodTransfer->rejection_reason)
                                        <tr>
                                            <th>Rejection Reason:</th>
                                            <td class="text-danger">{{ $bloodTransfer->rejection_reason }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if ($bloodTransfer->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Notes:</h5>
                                    <div class="alert alert-info">
                                        {{ $bloodTransfer->notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Action Buttons --}}
                            @if ($bloodTransfer->isPending())
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Actions:</h5>
                                    <div class="btn-group" role="group">
                                        <form action="{{ route('backend.admin.blood-transfers.approve', $bloodTransfer) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to approve this transfer? This will update the inventory automatically.')">
                                            @csrf
                                            <button type="submit" class="btn btn-success mx-2">
                                                <i class="fas fa-check"></i> Approve Transfer
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-danger mx-2" data-toggle="modal" data-target="#rejectModal">
                                            <i class="fas fa-times"></i> Reject Transfer
                                        </button>

                                        <form action="{{ route('backend.admin.blood-transfers.cancel', $bloodTransfer) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to cancel this transfer?')">
                                            @csrf
                                            <button type="submit" class="btn btn-secondary mx-2">
                                                <i class="fas fa-ban"></i> Cancel Transfer
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Reject Modal --}}
    @if ($bloodTransfer->isPending())
    <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Transfer Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('backend.admin.blood-transfers.reject', $bloodTransfer) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Rejection Reason <span class="text-danger">*</span></label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required
                                      placeholder="Please provide a reason for rejecting this transfer request..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
@endsection
