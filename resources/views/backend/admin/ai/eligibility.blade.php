@extends('backend.layouts.master')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">AI Donor Eligibility Predictor</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">AI Eligibility</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-robot mr-1"></i>
                                AI Donor Eligibility Predictor
                            </h3>
                        </div>
                        <div class="card-body">
                            <form id="eligibilityForm">
                                @csrf
                                <div class="form-group">
                                    <label for="donor_id">Select Donor</label>
                                    <select class="form-control" id="donor_id" name="donor_id" required>
                                        <option value="">Select a Donor</option>
                                        @foreach (\App\Models\Donor::all() as $donor)
                                            <option value="{{ $donor->id }}">
                                                {{ $donor->first_name }} {{ $donor->last_name }}
                                                ({{ $donor->userBloodGroup->value ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-brain mr-1"></i>
                                        AI Eligibility Prediction
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                AI Analysis Factors
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Our AI analyzes donor eligibility using:</p>
                            <ul>
                                <li>Age and weight requirements</li>
                                <li>Medical history patterns</li>
                                <li>Donation frequency analysis</li>
                                <li>Hemoglobin levels</li>
                                <li>Blood pressure readings</li>
                                <li>Previous donation intervals</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- AI Results -->
            <div class="row" id="aiResults" style="display: none;">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                AI Eligibility Analysis
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="aiResultsContent">
                                <!-- AI results will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js')
    <script>
        $(document).ready(function() {
            $('#eligibilityForm').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const formData = new FormData(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Analyzing...').prop('disabled',
                    true);

                $.ajax({
                        url: '{{ route('backend.admin.ai.eligibility.predict') }}',
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .done(function(data) {
                        if (data.error) {
                            alert('Error: ' + data.error);
                        } else {
                            displayAIResults(data);
                        }
                    })
                    .fail(function(jqXHR) {
                        const message = jqXHR.responseJSON && jqXHR.responseJSON.message ?
                            jqXHR.responseJSON.message :
                            'Network response was not ok';
                        alert('Error predicting eligibility: ' + message);
                    })
                    .always(function() {
                        $submitBtn.html(originalText).prop('disabled', false);
                    });
            });

            function displayAIResults(data) {
                $('#aiResults').show();

                const statusClass = data.eligible ? 'success' : 'danger';
                const statusIcon = data.eligible ? 'check-circle' : 'times-circle';

                $('#aiResultsContent').html(`
                    <div class="alert alert-${statusClass}">
                        <h4><i class="fas fa-${statusIcon}"></i> ${data.eligible ? 'ELIGIBLE' : 'NOT ELIGIBLE'}</h4>
                        <p><strong>AI Confidence:</strong> ${data.confidence}%</p>
                        ${data.next_donation_date ? `<p><strong>Next Donation Date:</strong> ${data.next_donation_date}</p>` : ''}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h5>AI Analysis:</h5>
                            <ul class="list-group">
                                ${Array.isArray(data.reasons) ? data.reasons.map(reason => `<li class="list-group-item">${reason}</li>`).join('') : ''}
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h5>AI Recommendations:</h5>
                            <ul class="list-group">
                                ${Array.isArray(data.recommendations) ? data.recommendations.map(rec => `<li class="list-group-item">${rec}</li>`).join('') : ''}
                            </ul>
                        </div>
                    </div>
                `);
            }
        });
    </script>
@endsection
