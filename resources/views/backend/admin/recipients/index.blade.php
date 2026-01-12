@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex justify-content-between align-items-center">
                    <h1 class="mb-0">Recipient Management</h1>
                    <a href="{{ route('backend.admin.recipients.create') }}" class="btn btn-sm btn-primary">Add New Recipient</a>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Recipients</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- Success Alert --}}
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
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email or patient code"
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="blood_group" class="form-control">
                            <option value="">All Blood Groups</option>
                            @foreach ($bloodGroups as $bloodGroup)
                                <option value="{{ $bloodGroup->id }}"
                                    {{ request('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                    {{ $bloodGroup->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="gender" class="form-control">
                            <option value="">All Genders</option>
                            @foreach ($genders as $gender)
                                <option value="{{ $gender->id }}"
                                    {{ request('gender') == $gender->id ? 'selected' : '' }}>
                                    {{ $gender->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Statuses</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}"
                                    {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-between">
                    <button class="btn btn-sm btn-secondary">Apply Filters</button>
                    <a href="{{ route('backend.admin.recipients.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
                </div>
            </form>

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title mb-0">Recipient List</h3>
                </div>

                <div class="card-body p-0">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Patient Code</th>
                                <th>Name</th>
                                <th>Blood Group</th>
                                <th>Gender</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th width="180">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recipients as $recipient)
                                <tr>
                                    <td>{{ $recipient->id }}</td>
                                    <td>{{ $recipient->patient_code }}</td>
                                    <td>{{ $recipient->full_name }}</td>
                                    <td>
                                        {{ $recipient->userBloodGroup?->name ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $recipient->userGender?->name ?? '-' }}
                                    </td>
                                    <td>{{ $recipient->contact_number }}</td>
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
                                    <td>{{ $recipient->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('backend.admin.recipients.show', $recipient->id) }}"
                                            class="btn btn-xs btn-info">View</a>
                                        <a href="{{ route('backend.admin.recipients.edit', $recipient->id) }}"
                                            class="btn btn-xs btn-warning">Edit</a>
                                        <form action="{{ route('backend.admin.recipients.destroy', $recipient->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-xs btn-danger"
                                                onclick="return confirm('Delete recipient?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">No recipients found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($recipients->hasPages())
                    <div class="card-footer clearfix">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Showing {{ $recipients->firstItem() }} to {{ $recipients->lastItem() }} of {{ $recipients->total() }}
                                results
                            </div>
                            <div>
                                {{ $recipients->withQueryString()->onEachSide(1)->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
