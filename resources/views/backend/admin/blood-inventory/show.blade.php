@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Blood Stock Details</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-inventory.index') }}">Blood Inventory</a></li>
                        <li class="breadcrumb-item active">Blood Stock Details</li>
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
                            <h3 class="card-title">Inventory ID: {{ $bloodInventory->inventory_id }}</h3>
                            <div class="card-tools">
                                <a href="{{ route('backend.admin.blood-inventory.edit', $bloodInventory) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="{{ route('backend.admin.blood-inventory.index') }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Inventory
                                </a>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Inventory ID:</th>
                                            <td>{{ $bloodInventory->inventory_id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Blood Group:</th>
                                            <td>
                                                <span class="badge badge-primary">
                                                    {{ $bloodInventory->bloodGroup->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Blood Type:</th>
                                            <td>
                                                <span class="badge badge-info">
                                                    {{ $bloodInventory->bloodType->name ?? 'Whole Blood' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Quantity:</th>
                                            <td>
                                                <strong class="text-primary">{{ $bloodInventory->quantity }}</strong>
                                                {{ $bloodInventory->unit }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Collection Date:</th>
                                            <td>{{ $bloodInventory->collection_date->format('F d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Expiry Date:</th>
                                            <td>{{ $bloodInventory->expiry_date->format('F d, Y') }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="40%">Storage Location:</th>
                                            <td>{{ $bloodInventory->storage_location ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Storage Temperature:</th>
                                            <td>
                                                @if($bloodInventory->temperature)
                                                    {{ $bloodInventory->temperature }}°C
                                                @else
                                                    Not specified
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td>
                                                @if($bloodInventory->status)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-secondary">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created By:</th>
                                            <td>{{ $bloodInventory->createBy->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Created At:</th>
                                            <td>{{ $bloodInventory->created_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @if($bloodInventory->updated_by)
                                        <tr>
                                            <th>Last Updated By:</th>
                                            <td>{{ $bloodInventory->updateBy->full_name ?? 'Unknown' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated:</th>
                                            <td>{{ $bloodInventory->updated_at->format('F d, Y \a\t g:i A') }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($bloodInventory->notes)
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <h5>Notes:</h5>
                                    <div class="alert alert-info">
                                        {{ $bloodInventory->notes }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <h5>Quick Actions:</h5>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('backend.admin.blood-inventory.edit', $bloodInventory) }}"
                                           class="btn btn-warning mx-2">
                                            <i class="fas fa-edit"></i> Edit Details
                                        </a>

                                        <form action="{{ route('backend.admin.blood-inventory.toggle-status', $bloodInventory) }}"
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn {{ $bloodInventory->status ? 'btn-secondary' : 'btn-success' }} mx-2">
                                                <i class="fas fa-{{ $bloodInventory->status ? 'pause' : 'play' }}"></i>
                                                {{ $bloodInventory->status ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('backend.admin.blood-inventory.destroy', $bloodInventory) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this inventory item?')">
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



