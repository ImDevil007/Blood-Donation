@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Add Blood Stock</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-inventory.index') }}">Blood Inventory</a></li>
                        <li class="breadcrumb-item active">Add Blood Stock</li>
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
                            <h3 class="card-title">Blood Stock Information</h3>
                        </div>

                        <form action="{{ route('backend.admin.blood-inventory.store') }}" method="POST">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="blood_group">Blood Group <span class="text-danger">*</span></label>
                                            <select name="blood_group" id="blood_group" class="form-control @error('blood_group') is-invalid @enderror" required>
                                                <option value="">Select Blood Group</option>
                                                @foreach ($bloodGroups as $bloodGroup)
                                                    <option value="{{ $bloodGroup->id }}" {{ old('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                                        {{ $bloodGroup->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('blood_group')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="blood_type">Blood Type</label>
                                            <select name="blood_type" id="blood_type" class="form-control @error('blood_type') is-invalid @enderror">
                                                <option value="">Select Blood Type (Optional)</option>
                                                @foreach ($bloodTypes as $bloodType)
                                                    <option value="{{ $bloodType->id }}" {{ old('blood_type') == $bloodType->id ? 'selected' : '' }}>
                                                        {{ $bloodType->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('blood_type')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="quantity">Quantity <span class="text-danger">*</span></label>
                                            <input type="number" name="quantity" id="quantity"
                                                   class="form-control @error('quantity') is-invalid @enderror"
                                                   value="{{ old('quantity') }}"
                                                   step="0.01" min="0.01" max="9999.99" required>
                                            @error('quantity')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="unit">Unit <span class="text-danger">*</span></label>
                                            <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" required>
                                                <option value="units" {{ old('unit') == 'units' ? 'selected' : '' }}>Units</option>
                                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>Milliliters (ml)</option>
                                                <option value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                                <option value="pints" {{ old('unit') == 'pints' ? 'selected' : '' }}>Pints</option>
                                            </select>
                                            @error('unit')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="temperature">Storage Temperature (°C)</label>
                                            <input type="number" name="temperature" id="temperature"
                                                   class="form-control @error('temperature') is-invalid @enderror"
                                                   value="{{ old('temperature', '4') }}"
                                                   step="0.1" min="-50" max="50">
                                            @error('temperature')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="collection_date">Collection Date <span class="text-danger">*</span></label>
                                            <input type="date" name="collection_date" id="collection_date"
                                                   class="form-control @error('collection_date') is-invalid @enderror"
                                                   value="{{ old('collection_date', date('Y-m-d')) }}"
                                                   max="{{ date('Y-m-d') }}" required>
                                            @error('collection_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expiry_date">Expiry Date <span class="text-danger">*</span></label>
                                            <input type="date" name="expiry_date" id="expiry_date"
                                                   class="form-control @error('expiry_date') is-invalid @enderror"
                                                   value="{{ old('expiry_date') }}" required>
                                            @error('expiry_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="storage_location">Storage Location</label>
                                            <input type="text" name="storage_location" id="storage_location"
                                                   class="form-control @error('storage_location') is-invalid @enderror"
                                                   value="{{ old('storage_location') }}"
                                                   placeholder="e.g., Refrigerator A, Shelf 2">
                                            @error('storage_location')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea name="notes" id="notes" rows="3"
                                                      class="form-control @error('notes') is-invalid @enderror"
                                                      placeholder="Additional notes about this blood stock...">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Add Blood Stock
                                </button>
                                <a href="{{ route('backend.admin.blood-inventory.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Inventory
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection




