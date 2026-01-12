@extends('backend.layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Blood Transfer Request</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.blood-transfers.index') }}">Blood Transfers</a></li>
                        <li class="breadcrumb-item active">Create Transfer</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Transfer Request Information</h3>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-ban"></i> Validation Error!</h5>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('backend.admin.blood-transfers.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Source Bank <span class="text-danger">*</span></label>
                                    <select name="source_bank" id="source_bank" class="form-control @error('source_bank') is-invalid @enderror" required>
                                        <option value="">Select Source Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank }}" {{ old('source_bank') == $bank ? 'selected' : '' }}>
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('source_bank')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Destination Bank <span class="text-danger">*</span></label>
                                    <select name="destination_bank" id="destination_bank" class="form-control @error('destination_bank') is-invalid @enderror" required>
                                        <option value="">Select Destination Bank</option>
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank }}" {{ old('destination_bank') == $bank ? 'selected' : '' }}>
                                                {{ $bank }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('destination_bank')
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
                                    <label>Blood Group <span class="text-danger">*</span></label>
                                    <select name="blood_group" id="blood_group" class="form-control @error('blood_group') is-invalid @enderror" required>
                                        <option value="">Select Blood Group</option>
                                        @foreach ($bloodGroups as $bloodGroup)
                                            <option value="{{ $bloodGroup->id }}" {{ old('blood_group') == $bloodGroup->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($bloodGroup->name)) }}
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
                                    <label>Blood Type <span class="text-danger">*</span></label>
                                    <select name="blood_type" id="blood_type" class="form-control @error('blood_type') is-invalid @enderror" required>
                                        <option value="">Select Blood Type</option>
                                        @foreach ($bloodTypes as $bloodType)
                                            <option value="{{ $bloodType->id }}" {{ old('blood_type') == $bloodType->id ? 'selected' : '' }}>
                                                {{ ucfirst(trans($bloodType->name)) }}
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
                                    <label>Available Stock</label>
                                    <div id="available-stock" class="form-control" style="background-color: #e9ecef;" readonly>
                                        <span class="text-muted">Select source bank and blood group to see available stock</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Quantity <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                           value="{{ old('quantity') }}" step="0.01" min="0.01" max="9999.99" required>
                                    @error('quantity')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unit <span class="text-danger">*</span></label>
                                    <select name="unit" id="unit" class="form-control @error('unit') is-invalid @enderror" required>
                                        <option value="units" {{ old('unit', 'units') == 'units' ? 'selected' : '' }}>Units</option>
                                        <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>ML</option>
                                        <option value="bags" {{ old('unit') == 'bags' ? 'selected' : '' }}>Bags</option>
                                    </select>
                                    @error('unit')
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
                                    <label>Storage Temperature (°C)</label>
                                    <input type="number" name="temperature" id="temperature" class="form-control @error('temperature') is-invalid @enderror" 
                                           value="{{ old('temperature', '4') }}" step="0.1" min="-50" max="50"
                                           placeholder="Default: 4°C">
                                    @error('temperature')
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
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about this transfer">{{ old('notes') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Create Transfer Request</button>
                        <a href="{{ route('backend.admin.blood-transfers.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

@section('custom-js')
<script>
    $(document).ready(function() {
        var sourceBank = $('#source_bank');
        var bloodGroup = $('#blood_group');
        var bloodType = $('#blood_type');
        var availableStockDiv = $('#available-stock');

        function checkAvailableStock() {
            var bank = sourceBank.val();
            var bg = bloodGroup.val();
            var bt = bloodType.val();

            if (!bank || !bg || !bt) {
                availableStockDiv.html('<span class="text-muted">Select source bank, blood group and blood type to see available stock</span>');
                return;
            }

            $.ajax({
                url: '{{ route("backend.admin.blood-transfers.available-stock") }}',
                method: 'GET',
                data: {
                    bank: bank,
                    blood_group: bg,
                    blood_type: bt
                },
                success: function(response) {
                    var stock = parseFloat(response.available_stock);
                    if (stock > 0) {
                        availableStockDiv.html('<span class="text-success"><strong>Available: ' + stock.toFixed(2) + ' units</strong></span>');
                    } else {
                        availableStockDiv.html('<span class="text-danger"><strong>No stock available</strong></span>');
                    }
                },
                error: function() {
                    availableStockDiv.html('<span class="text-danger">Error checking stock</span>');
                }
            });
        }

        sourceBank.on('change', checkAvailableStock);
        bloodGroup.on('change', checkAvailableStock);
        bloodType.on('change', checkAvailableStock);
    });
</script>
@endsection
