@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Blood Transfer Management</h1>
                    <a href="{{ route('backend.admin.blood-transfers.create') }}" class="btn btn-sm btn-primary">Create Transfer</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blood Transfers</li>
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

            @if (session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icon fas fa-ban"></i>
                    {{ session('error') ?? $errors->first('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Filter Form --}}
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-2">
                        <input type="text" name="search" class="form-control" placeholder="Search Transfer ID or Bank"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="blood_group" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach ($bloodGroups as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}"
                                    {{ request('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                    {{ ucfirst(trans($bloodGroup->name)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="source_bank" class="form-control">
                            <option value="">All Source Banks</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}"
                                    {{ request('source_bank') == $bank ? 'selected' : '' }}>
                                    {{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="destination_bank" class="form-control">
                            <option value="">All Destination Banks</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank }}"
                                    {{ request('destination_bank') == $bank ? 'selected' : '' }}>
                                    {{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('backend.admin.blood-transfers.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>

            {{-- Transfers Table --}}
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Transfer ID</th>
                                <th>Source Bank</th>
                                <th>Destination Bank</th>
                                <th>Blood Group</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Requested By</th>
                                <th>Requested Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->transfer_id }}</td>
                                    <td>{{ $transfer->source_bank }}</td>
                                    <td>{{ $transfer->destination_bank }}</td>
                                    <td>{{ ucfirst(trans($transfer->bloodGroup->name ?? 'N/A')) }}</td>
                                    <td>{{ $transfer->quantity }} {{ $transfer->unit }}</td>
                                    <td>
                                        @if ($transfer->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif ($transfer->status == 'approved')
                                            <span class="badge badge-info">Approved</span>
                                        @elseif ($transfer->status == 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif ($transfer->status == 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-secondary">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>{{ $transfer->requestedBy->first_name ?? 'N/A' }} {{ $transfer->requestedBy->last_name ?? '' }}</td>
                                    <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('backend.admin.blood-transfers.show', $transfer->id) }}"
                                            class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No blood transfers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{ $transfers->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
