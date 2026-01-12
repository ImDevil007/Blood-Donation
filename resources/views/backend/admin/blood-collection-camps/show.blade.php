@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Camp Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-collection-camps.index') }}">Collection Camps</a></li>
                        <li class="breadcrumb-item active">Camp Details</li>
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
                            <h3 class="card-title">Camp ID: {{ $bloodCollectionCamp->camp_id }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.blood-collection-camps.edit', $bloodCollectionCamp) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.blood-collection-camps.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Camps
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Camp ID:</th>
                                            <td>{{ $bloodCollectionCamp->camp_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Camp Name:</th>
                                            <td><strong>{{ $bloodCollectionCamp->name }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Location:</th>
                                            <td>{{ $bloodCollectionCamp->location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Start Date:</th>
                                            <td>{{ $bloodCollectionCamp->start_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Date:</th>
                                            <td>{{ $bloodCollectionCamp->end_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Duration:</th>
                                            <td>{{ $bloodCollectionCamp->getDurationInDays() }} day(s)</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Start Time:</th>
                                            <td>{{ $bloodCollectionCamp->start_time->format('g:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>End Time:</th>
                                            <td>{{ $bloodCollectionCamp->end_time->format('g:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Target Donors:</th>
                                            <td><strong class="text-primary">{{ $bloodCollectionCamp->target_donors }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Actual Donors:</th>
                                            <td><strong class="text-success">{{ $bloodCollectionCamp->actual_donors }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Success Rate:</th>
                                            <td>
                                                <span class="badge badge-{{ $bloodCollectionCamp->getSuccessRate() >= 80 ? 'success' : ($bloodCollectionCamp->getSuccessRate() >= 60 ? 'warning' : 'danger') }}">
                                                    {{ $bloodCollectionCamp->getSuccessRate() }}%
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                <span class="badge badge-{{ $bloodCollectionCamp->getStatusBadge() }}">
                                                    {{ ucfirst($bloodCollectionCamp->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Organizer:</th>
                                            <td>{{ $bloodCollectionCamp->organizer_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Contact:</th>
                                            <td>{{ $bloodCollectionCamp->organizer_contact }}</td>
                                        </tr>
                                        <tr>
                                            <th>Collected Units:</th>
                                            <td><strong class="text-info">{{ $bloodCollectionCamp->collected_units }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Created By:</th>
                                            <td>{{ $bloodCollectionCamp->createBy->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ $bloodCollectionCamp->created_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @if($bloodCollectionCamp->updated_by)
                                        <tr>
                                            <th>Last Updated By:</th>
                                            <td>{{ $bloodCollectionCamp->updateBy->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $bloodCollectionCamp->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($bloodCollectionCamp->description)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Description:</h5>
                                    <div class="alert alert-info">
                                        {{ $bloodCollectionCamp->description }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($bloodCollectionCamp->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Notes:</h5>
                                    <div class="alert alert-warning">
                                        {{ $bloodCollectionCamp->notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Quick Actions:</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('backend.admin.blood-collection-camps.edit', $bloodCollectionCamp) }}"
                                           class="btn btn-warning mx-2">
                                            <i class="fas fa-edit"></i> Edit Details
                                        </a>

                                        <form action="{{ route('backend.admin.blood-collection-camps.update-status', $bloodCollectionCamp) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <select name="status" class="form-control d-inline-block mx-2" style="width: auto;" onchange="this.form.submit()">
                                                <option value="scheduled" {{ $bloodCollectionCamp->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                                <option value="ongoing" {{ $bloodCollectionCamp->status == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                                <option value="completed" {{ $bloodCollectionCamp->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $bloodCollectionCamp->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>

                                        <form action="{{ route('backend.admin.blood-collection-camps.destroy', $bloodCollectionCamp) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this camp?')">
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
