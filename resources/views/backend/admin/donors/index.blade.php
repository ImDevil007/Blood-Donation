@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Donor Management</h1>
                    <a href="{{ route('backend.admin.donors.create') }}" class="btn btn-sm btn-primary">Register New Donor</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Donors</li>
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
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, or donor ID"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="blood_group" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach ($bloodGroups as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}"
                                    {{ request('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                    {{ $bloodGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="gender" class="form-control">
                            <option value="">All Genders</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}"
                                    {{ request('gender') == $gender->id ? 'selected' : '' }}>
                                    {{ $gender->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="eligibility" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" {{ request('eligibility') == '1' ? 'selected' : '' }}>Eligible</option>
                            <option value="0" {{ request('eligibility') == '0' ? 'selected' : '' }}>Ineligible</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-secondary">Apply Filters</button>
                        <a href="{{ route('backend.admin.donors.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Donor List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Donor ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Blood Group</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Total Donations</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($donors as $donor)
                                <tr>
                                    <td>{{ $donor->donor_id }}</td>
                                    <td>{{ $donor->full_name }}</td>
                                    <td>{{ $donor->email }}</td>
                                    <td>{{ $donor->phone }}</td>
                                    <td>{{ $donor->userBloodGroup?->name ?? '-' }}</td>
                                    <td>{{ $donor->age }}</td>
                                    <td>{{ $donor->userGender?->name ?? '-' }}</td>
                                    <td>{{ $donor->total_donations }}</td>
                                    <td>
                                        @if($donor->is_eligible)
                                            <span class="badge badge-success">Eligible</span>
                                        @else
                                            <span class="badge badge-danger">Ineligible</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.admin.donors.show', $donor->id) }}"
                                            class="btn btn-xs btn-info">View</a>
                                        <a href="{{ route('backend.admin.donors.edit', $donor->id) }}"
                                            class="btn btn-xs btn-warning">Edit</a>
                                        <form action="{{ route('backend.admin.donors.destroy', $donor->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs btn-danger"
                                                onclick="return confirm('Delete donor?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">No donors found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($donors->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $donors->firstItem() }} to {{ $donors->lastItem() }} of {{ $donors->total() }}
                                results
                            </div>
                            <div>
                                {{ $donors->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
