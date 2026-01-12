@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Recipient Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.recipients.index') }}">Recipients</a></li>
                        <li class="breadcrumb-item active">Recipient Details</li>
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

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Patient Code: {{ $recipient->patient_code }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.recipients.edit', $recipient) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.recipients.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Recipients
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Patient Code:</th>
                                            <td>{{ $recipient->patient_code }}</td>
                                        </tr>
                                        <tr>
                                            <th>Full Name:</th>
                                            <td><strong>{{ $recipient->full_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td>{{ $recipient->dob?->format('F d, Y') ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Age:</th>
                                            <td><strong class="text-primary">{{ $recipient->age ?? '-' }} years</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Gender:</th>
                                            <td>{{ $recipient->userGender?->name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood Group:</th>
                                            <td><strong class="text-danger">{{ $recipient->userBloodGroup?->name ?? '-' }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Contact Number:</th>
                                            <td>{{ $recipient->contact_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td>{{ $recipient->email ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>City:</th>
                                            <td>{{ $recipient->city ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>District:</th>
                                            <td>{{ $recipient->district ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Request Status:</th>
                                            <td>
                                                <span class="badge
                                                    @if($recipient->request_status=='pending') badge-warning
                                                    @elseif($recipient->request_status=='accepted') badge-info
                                                    @elseif($recipient->request_status=='fulfilled') badge-success
                                                    @elseif($recipient->request_status=='rejected') badge-danger
                                                    @endif">
                                                    {{ ucfirst($recipient->request_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($recipient->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Hospital Name:</th>
                                            <td>{{ $recipient->hospital_name ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Doctor Name:</th>
                                            <td><strong>{{ $recipient->doctor_name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Admission Date:</th>
                                            <td>{{ $recipient->admission_date?->format('F d, Y') ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood Required Date:</th>
                                            <td><strong class="text-warning">{{ $recipient->blood_required_date?->format('F d, Y') ?? '-' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Blood Quantity Required:</th>
                                            <td><strong class="text-info">{{ $recipient->blood_quantity_required ?? '-' }} units</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Created By:</th>
                                            <td>{{ $recipient->createBy?->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ $recipient->created_at?->format('F d, Y \a\t g:i A') ?? '-' }}</td>
                                        </tr>
                                        @if($recipient->updated_by)
                                        <tr>
                                            <th>Last Updated By:</th>
                                            <td>{{ $recipient->updateBy?->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $recipient->updated_at?->format('F d, Y \a\t g:i A') ?? '-' }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($recipient->address)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Address:</h5>
                                    <div class="alert alert-info">
                                        {{ $recipient->address }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($recipient->diagnosis)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Diagnosis:</h5>
                                    <div class="alert alert-warning">
                                        {{ $recipient->diagnosis }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($recipient->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Notes:</h5>
                                    <div class="alert alert-secondary">
                                        {{ $recipient->notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Quick Actions:</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('backend.admin.recipients.edit', $recipient) }}"
                                           class="btn btn-warning mx-2">
                                            <i class="fas fa-edit"></i> Edit Details
                                        </a>

                                        <form action="{{ route('backend.admin.recipients.toggle-status', $recipient) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-{{ $recipient->status ? 'secondary' : 'success' }} mx-2">
                                                <i class="fas fa-{{ $recipient->status ? 'pause' : 'play' }}"></i>
                                                {{ $recipient->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('backend.admin.recipients.destroy', $recipient) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this recipient?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger mx-2">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
