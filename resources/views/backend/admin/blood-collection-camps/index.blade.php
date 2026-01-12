@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Blood Collection Camps</h1>
                    <a href="{{ route('backend.admin.blood-collection-camps.create') }}" class="btn btn-sm btn-primary">Schedule New Camp</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Collection Camps</li>
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
                        <input type="text" name="search" class="form-control" placeholder="Search by camp name, location, or organizer"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                            <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="date_filter" class="form-control">
                            <option value="">All Dates</option>
                            <option value="upcoming" {{ request('date_filter') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                            <option value="ongoing" {{ request('date_filter') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('date_filter') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="past" {{ request('date_filter') == 'past' ? 'selected' : '' }}>Past</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-secondary">Apply Filters</button>
                        <a href="{{ route('backend.admin.blood-collection-camps.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                    </div>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Collection Camps List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Camp ID</th>
                                <th>Camp Name</th>
                                <th>Location</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Target/Actual</th>
                                <th>Status</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($camps as $camp)
                                <tr>
                                    <td>{{ $camp->camp_id }}</td>
                                    <td>{{ $camp->name }}</td>
                                    <td>{{ $camp->location }}</td>
                                    <td>{{ $camp->start_date?->format('M d, Y') }}</td>
                                    <td>{{ $camp->end_date?->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $camp->actual_donors }}/{{ $camp->target_donors }}</span>
                                        @if($camp->target_donors > 0)
                                            <small class="text-muted">({{ $camp->getSuccessRate() }}%)</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $camp->getStatusBadge() }}">
                                            {{ ucfirst($camp->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.admin.blood-collection-camps.show', $camp->id) }}"
                                            class="btn btn-xs btn-info">View</a>
                                        <a href="{{ route('backend.admin.blood-collection-camps.edit', $camp->id) }}"
                                            class="btn btn-xs btn-warning">Edit</a>
                                        <form action="{{ route('backend.admin.blood-collection-camps.destroy', $camp->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs btn-danger"
                                                onclick="return confirm('Delete this camp?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No collection camps found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($camps->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $camps->firstItem() }} to {{ $camps->lastItem() }} of {{ $camps->total() }}
                                results
                            </div>
                            <div>
                                {{ $camps->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
