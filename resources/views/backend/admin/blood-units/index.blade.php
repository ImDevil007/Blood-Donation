@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Blood Units Management</h1>
                    <a href="{{ route('backend.admin.blood-units.create') }}" class="btn btn-sm btn-primary">Add Blood Unit</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Blood Units</li>
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
                        <input type="text" name="search" class="form-control" placeholder="Search by unit ID or donor name"
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
                        <select name="blood_type" class="form-control">
                            <option value="">All Blood Types</option>
                            @foreach ($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}"
                                    {{ request('blood_type') == $bloodType->id ? 'selected' : '' }}>
                                    {{ $bloodType->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-secondary">Apply Filters</button>
                        <a href="{{ route('backend.admin.blood-units.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Blood Units List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Unit ID</th>
                                <th>Donor</th>
                                <th>Blood Group</th>
                                <th>Blood Type</th>
                                <th>Volume (ml)</th>
                                <th>Collection Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bloodUnits as $bloodUnit)
                                <tr>
                                    <td>{{ $bloodUnit->unit_id }}</td>
                                    <td>{{ $bloodUnit->donor->full_name }}</td>
                                    <td>{{ $bloodUnit->bloodGroup?->name ?? '-' }}</td>
                                    <td>{{ $bloodUnit->bloodType?->name ?? 'Whole Blood' }}</td>
                                    <td>{{ $bloodUnit->volume }}</td>
                                    <td>{{ $bloodUnit->collection_date->format('M d, Y') }}</td>
                                    <td>{{ $bloodUnit->expiry_date->format('M d, Y') }}</td>
                                    <td>
                                        @if($bloodUnit->status)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.admin.blood-units.show', $bloodUnit->id) }}"
                                            class="btn btn-xs btn-info">View</a>
                                        <a href="{{ route('backend.admin.blood-units.edit', $bloodUnit->id) }}"
                                            class="btn btn-xs btn-warning">Edit</a>
                                        <form action="{{ route('backend.admin.blood-units.destroy', $bloodUnit->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs btn-danger"
                                                onclick="return confirm('Delete blood unit?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No blood units found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($bloodUnits->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $bloodUnits->firstItem() }} to {{ $bloodUnits->lastItem() }} of {{ $bloodUnits->total() }}
                                results
                            </div>
                            <div>
                                {{ $bloodUnits->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

