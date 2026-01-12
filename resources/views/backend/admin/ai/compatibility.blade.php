@extends('backend.layouts.master')

@section('content')
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">AI Blood Compatibility Checker</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">AI Compatibility</li>
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
                                AI Blood Compatibility Checker
                            </h3>
                        </div>
                        <div class="card-body">
                            <form id="compatibilityForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="recipient_blood_type">Recipient Blood Type</label>
                                            <select class="form-control" id="recipient_blood_type"
                                                name="recipient_blood_type" required>
                                                <option value="">Select Recipient Blood Type</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="donor_blood_type">Donor Blood Type</label>
                                            <select class="form-control" id="donor_blood_type" name="donor_blood_type"
                                                required>
                                                <option value="">Select Donor Blood Type</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-brain mr-1"></i>
                                            AI Compatibility Check
                                        </button>
                                    </div>
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
                                How AI Works
                            </h3>
                        </div>
                        <div class="card-body">
                            <p>Our AI analyzes blood compatibility using:</p>
                            <ul>
                                <li>Universal blood type rules</li>
                                <li>Rh factor compatibility</li>
                                <li>Risk assessment algorithms</li>
                                <li>Inventory availability check</li>
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
                                AI Analysis Results
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
            $('#compatibilityForm').on('submit', function(e) {
                e.preventDefault();

                const $form = $(this);
                const formData = new FormData(this);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalText = $submitBtn.html();

                $submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i>Analyzing...').prop('disabled',
                    true);

                $.ajax({
                        url: '{{ route('backend.admin.ai.compatibility.check') }}',
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
                        alert('Error checking compatibility: ' + message);
                    })
                    .always(function() {
                        $submitBtn.html(originalText).prop('disabled', false);
                    });
            });

            function displayAIResults(data) {
                $('#aiResults').show();

                const statusClass = data.compatible ? 'success' : 'danger';
                const statusIcon = data.compatible ? 'check-circle' : 'times-circle';

                $('#aiResultsContent').html(`
                    <div class="alert alert-${statusClass}">
                        <h4><i class="fas fa-${statusIcon}"></i> ${data.compatible ? 'COMPATIBLE' : 'NOT COMPATIBLE'}</h4>
                        <p><strong>Risk Level:</strong> ${data.risk_level}</p>
                        <p>${data.explanation}</p>
                    </div>
                    <div class="row">
                        <div class="col-12">
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
