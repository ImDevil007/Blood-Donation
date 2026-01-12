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
                    <h1>Test Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-tests.index') }}">Blood Tests</a></li>
                        <li class="breadcrumb-item active">Test Details</li>
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
                            <h3 class="card-title">Test ID: {{ $bloodTest->test_id }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.blood-tests.edit', $bloodTest) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.blood-tests.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Tests
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Test ID:</th>
                                            <td>{{ $bloodTest->test_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood Unit:</th>
                                            <td>
                                                <a href="{{ route('backend.admin.blood-units.show', $bloodTest->bloodnit->id) }}" class="text-primary">
                                                    {{ $bloodTest->bloodnit->unit_id }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Donor:</th>
                                            <td>{{ $bloodTest->bloodUnit?->donor?->full_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Test Date:</th>
                                            <td>{{ $bloodTest->test_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Technician:</th>
                                            <td>{{ $bloodTest->technician?->full_name ?? 'Not Assigned' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Lab Reference:</th>
                                            <td>{{ $bloodTest->lab_reference ?? 'Not provided' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Overall Status:</th>
                                            <td>
                                                <span class="badge badge-{{ $bloodTest->getStatusBadge() }}">
                                                    {{ ucfirst($bloodTest->overall_status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $bloodTest->createBy?->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ $bloodTest->created_at?->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @if($bloodTest->updated_by)
                                        <tr>
                                            <th>Last Updated By:</th>
                                            <td>{{ $bloodTest->updateBy?->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $bloodTest->updated_at?->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Test Results</h5>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-{{ $bloodTest->hiv_result == 'positive' ? 'danger' : ($bloodTest->hiv_result == 'negative' ? 'success' : 'warning') }}">
                                                    <i class="fas fa-virus"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">HIV</span>
                                                    <span class="info-box-number">{{ ucfirst($bloodTest->hiv_result) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-{{ $bloodTest->hepatitis_b_result == 'positive' ? 'danger' : ($bloodTest->hepatitis_b_result == 'negative' ? 'success' : 'warning') }}">
                                                    <i class="fas fa-lungs-virus"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Hepatitis B</span>
                                                    <span class="info-box-number">{{ ucfirst($bloodTest->hepatitis_b_result) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-{{ $bloodTest->hepatitis_c_result == 'positive' ? 'danger' : ($bloodTest->hepatitis_c_result == 'negative' ? 'success' : 'warning') }}">
                                                    <i class="fas fa-lungs-virus"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Hepatitis C</span>
                                                    <span class="info-box-number">{{ ucfirst($bloodTest->hepatitis_c_result) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-{{ $bloodTest->syphilis_result == 'positive' ? 'danger' : ($bloodTest->syphilis_result == 'negative' ? 'success' : 'warning') }}">
                                                    <i class="fas fa-bacteria"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Syphilis</span>
                                                    <span class="info-box-number">{{ ucfirst($bloodTest->syphilis_result) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-{{ $bloodTest->malaria_result == 'positive' ? 'danger' : ($bloodTest->malaria_result == 'negative' ? 'success' : 'warning') }}">
                                                    <i class="fas fa-bug"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Malaria</span>
                                                    <span class="info-box-number">{{ ucfirst($bloodTest->malaria_result) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-info">
                                                    <i class="fas fa-tint"></i>
                                                </span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Blood Group</span>
                                                    <span class="info-box-number">{{ $bloodTest->blood_group ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($bloodTest->test_notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Test Notes:</h5>
                                    <div class="alert alert-info">
                                        {{ $bloodTest->test_notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Quick Actions:</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('backend.admin.blood-tests.edit', $bloodTest) }}"
                                           class="btn btn-warning mx-2">
                                            <i class="fas fa-edit"></i> Edit Test
                                        </a>

                                        @if($bloodTest->overall_status == 'pending')
                                            <form action="{{ route('backend.admin.blood-tests.quarantine', $bloodTest) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger mx-2"
                                                        onclick="return confirm('Quarantine this blood unit? This will mark it as unfit for use.')">
                                                    <i class="fas fa-ban"></i> Quarantine
                                                </button>
                                            </form>

                                            <form action="{{ route('backend.admin.blood-tests.approve', $bloodTest) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success mx-2"
                                                        onclick="return confirm('Approve this blood unit? This will mark it as safe for use.')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('backend.admin.blood-tests.destroy', $bloodTest) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this test?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
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
