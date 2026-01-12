@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
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

            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blood Unit Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-units.index') }}">Blood Units</a></li>
                        <li class="breadcrumb-item active">Blood Unit Details</li>
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
                            <h3 class="card-title">Blood Unit ID: {{ $bloodUnit->unit_id }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.blood-units.edit', $bloodUnit) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.blood-units.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Units
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Unit ID:</th>
                                            <td>{{ $bloodUnit->unit_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Donor:</th>
                                            <td>{{ $bloodUnit->donor?->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood Group:</th>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $bloodUnit->bloodGroup?->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Blood Type:</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $bloodUnit->bloodType?->name ?? 'Whole Blood' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Volume:</th>
                                            <td>
                                                <strong class="text-primary">{{ $bloodUnit->volume }}</strong> ml
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Collection Date:</th>
                                            <td>{{ $bloodUnit->collection_date?->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Expiry Date:</th>
                                            <td>{{ $bloodUnit->expiry_date?->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Storage Location:</th>
                                            <td>{{ $bloodUnit->storage_location ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Temperature:</th>
                                            <td>
                                                @if($bloodUnit->temperature)
                                                    {{ $bloodUnit->temperature }}°C
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Hemoglobin Level:</th>
                                            <td>
                                                @if($bloodUnit->hemoglobin_level)
                                                    {{ $bloodUnit->hemoglobin_level }} g/dL
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($bloodUnit->is_used)
                                                    <span class="badge badge-warning">Used</span>
                                                @elseif($bloodUnit->isExpired())
                                                    <span class="badge badge-danger">Expired</span>
                                                @elseif($bloodUnit->isExpiringSoon())
                                                    <span class="badge badge-warning">Expiring Soon</span>
                                                @else
                                                    <span class="badge badge-success">Available</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($bloodUnit->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Notes:</h5>
                                    <div class="alert alert-info">
                                        {{ $bloodUnit->notes }}
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
@endsection
