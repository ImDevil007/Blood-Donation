@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Blood Tests</h1>
                    <a href="{{ route('backend.admin.blood-tests.create') }}" class="btn btn-sm btn-primary">Add New Test</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blood Tests</li>
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

            {{-- Filter Form --}}
            <form method="GET" class="mb-3">
                <div class="row">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by test ID, unit ID, or lab reference"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="quarantined" {{ request('status') == 'quarantined' ? 'selected' : '' }}>Quarantined</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="technician" class="form-control">
                            <option value="">All Technicians</option>
                            @foreach ($technicians as $technician)
                                <option value="{{ $technician->id }}" {{ request('technician') == $technician->id ? 'selected' : '' }}>
                                    {{ $technician->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-secondary">Apply Filters</button>
                        <a href="{{ route('backend.admin.blood-tests.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Blood Tests List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Test ID</th>
                                <th>Blood Unit</th>
                                <th>Donor</th>
                                <th>Test Date</th>
                                <th>Technician</th>
                                <th>Results</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tests as $test)
                                <tr>
                                    <td>{{ $test->test_id }}</td>
                                    <td>{{ $test->bloodUnit?->unit_id }}</td>
                                    <td>{{ $test->bloodUnit?->donor?->full_name }}</td>
                                    <td>{{ $test->test_date->format('M d, Y') }}</td>
                                    <td>{{ $test->technician?->full_name ?? 'Not Assigned' }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @if($test->hiv_result)
                                                <span class="badge badge-{{ $test->hiv_result == 'positive' ? 'danger' : 'success' }}">HIV</span>
                                            @endif
                                            @if($test->hepatitis_b_result)
                                                <span class="badge badge-{{ $test->hepatitis_b_result == 'positive' ? 'danger' : 'success' }}">HepB</span>
                                            @endif
                                            @if($test->hepatitis_c_result)
                                                <span class="badge badge-{{ $test->hepatitis_c_result == 'positive' ? 'danger' : 'success' }}">HepC</span>
                                            @endif
                                            @if($test->syphilis_result)
                                                <span class="badge badge-{{ $test->syphilis_result == 'positive' ? 'danger' : 'success' }}">Syphilis</span>
                                            @endif
                                            @if($test->malaria_result)
                                                <span class="badge badge-{{ $test->malaria_result == 'positive' ? 'danger' : 'success' }}">Malaria</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $test->getStatusBadge() }}">
                                            {{ ucfirst($test->overall_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.admin.blood-tests.show', $test->id) }}"
                                            class="btn btn-xs btn-info">View</a>
                                        <a href="{{ route('backend.admin.blood-tests.edit', $test->id) }}"
                                            class="btn btn-xs btn-warning">Edit</a>
                                        @if($test->overall_status == 'pending')
                                            <form action="{{ route('backend.admin.blood-tests.quarantine', $test->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button class="btn btn-xs btn-danger"
                                                    onclick="return confirm('Quarantine this blood unit?')">Quarantine</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No blood tests found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($tests->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $tests->firstItem() }} to {{ $tests->lastItem() }} of {{ $tests->total() }}
                                results
                            </div>
                            <div>
                                {{ $tests->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
