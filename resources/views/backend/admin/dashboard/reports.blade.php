@extends('backend.layouts.master')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Reports & Analytics</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('backend.admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Reports</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
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
                    <!-- Report Generation Form -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-file-alt mr-1"></i>
                                Generate Report
                            </h3>
                        </div>
                        <div class="card-body">
                            <form id="reportForm" method="POST" action="{{ route('backend.admin.generate-report') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="report_type">Report Type</label>
                                            <select class="form-control" id="report_type" name="report_type" required>
                                                <option value="">Select Report Type</option>
                                                <option value="donors">Donor Report</option>
                                                <option value="blood_units">Blood Unit Report</option>
                                                <option value="donations">Donation Report</option>
                                                <option value="tests">Test Report</option>
                                                <option value="camps">Camp Report</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_from">Date From</label>
                                            <input type="date" class="form-control" id="date_from" name="date_from"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="date_to">Date To</label>
                                            <input type="date" class="form-control" id="date_to" name="date_to"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="format">Format</label>
                                            <select class="form-control" id="format" name="format" required>
                                                <option value="">Select Format</option>
                                                <option value="pdf">PDF</option>
                                                <option value="excel">Excel</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-download mr-1"></i>
                                            Generate Report
                                        </button>
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo mr-1"></i>
                                            Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Quick Reports -->
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-users mr-1"></i>
                                        Donor Reports
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donors', 'month')">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            This Month's Donors
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donors', 'year')">
                                            <i class="fas fa-calendar mr-2"></i>
                                            This Year's Donors
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donors', 'all')">
                                            <i class="fas fa-list mr-2"></i>
                                            All Donors
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-tint mr-1"></i>
                                        Blood Unit Reports
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('blood_units', 'month')">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            This Month's Units
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('blood_units', 'expiring')">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Expiring Soon
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('blood_units', 'available')">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Available Units
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-heart mr-1"></i>
                                        Donation Reports
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donations', 'month')">
                                            <i class="fas fa-calendar-alt mr-2"></i>
                                            This Month's Donations
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donations', 'year')">
                                            <i class="fas fa-calendar mr-2"></i>
                                            This Year's Donations
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action"
                                            onclick="quickReport('donations', 'all')">
                                            <i class="fas fa-list mr-2"></i>
                                            All Donations
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Report Results -->
                    <div class="card card-primary" id="reportResults" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Report Results
                            </h3>
                        </div>
                        <div class="card-body">
                            <div id="reportContent">
                                <!-- Report content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function resetForm() {
            document.getElementById('reportForm').reset();
            document.getElementById('reportResults').style.display = 'none';
        }

        function quickReport(type, period) {
            const today = new Date();
            let dateFrom, dateTo;

            switch (period) {
                case 'month':
                    dateFrom = new Date(today.getFullYear(), today.getMonth(), 1);
                    dateTo = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    break;
                case 'year':
                    dateFrom = new Date(today.getFullYear(), 0, 1);
                    dateTo = new Date(today.getFullYear(), 11, 31);
                    break;
                case 'all':
                    dateFrom = new Date('2020-01-01');
                    dateTo = today;
                    break;
                case 'expiring':
                    dateFrom = today;
                    dateTo = new Date(today.getTime() + (7 * 24 * 60 * 60 * 1000)); // 7 days from now
                    break;
                case 'available':
                    // For available units, we'll use a different approach
                    generateQuickReport(type, period);
                    return;
            }

            // Set form values
            document.getElementById('report_type').value = type;
            document.getElementById('date_from').value = dateFrom.toISOString().split('T')[0];
            document.getElementById('date_to').value = dateTo.toISOString().split('T')[0];
            document.getElementById('format').value = 'pdf';

            // Submit form
            document.getElementById('reportForm').submit();
        }

        function generateQuickReport(type, period) {
            // For special reports like 'available' units
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('report_type', type);
            formData.append('date_from', new Date().toISOString().split('T')[0]);
            formData.append('date_to', new Date().toISOString().split('T')[0]);
            formData.append('format', 'pdf');
            formData.append('special_period', period);

            fetch('{{ route('backend.admin.generate-report') }}', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    displayReportResults(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error generating report');
                });
        }

        function displayReportResults(data) {
            document.getElementById('reportResults').style.display = 'block';
            document.getElementById('reportContent').innerHTML = `
            <div class="alert alert-info">
                <h4>${data.report_type}</h4>
                <p><strong>Period:</strong> ${data.period}</p>
                <p><strong>Total Records:</strong> ${data.total_donors || data.total_units || data.total_donations || data.total_tests || data.total_camps}</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            ${Object.keys(data.data[0] || {}).map(key => ` < th > $ {
                    key.replace(/_/g, ' ').toUpperCase()
                } < /th>`).join('')} < /
            tr > <
            /thead> <
        tbody >
            $ {
                data.data.slice(0, 10).map(item => `
                                    <tr>
                                        ${Object.values(item).map(value => `<td>${value || 'N/A'}</td>`).join('')}
                                    </tr>
                                `).join('')
            } <
            /tbody> < /
            table > <
            /div>
        $ {
            data.data.length > 10 ?
                `<p class="text-muted">Showing first 10 records of ${data.data.length} total records.</p>` : ''
        }
        `;
            }

            // Form submission handler - allow normal submission for file downloads
            document.getElementById('reportForm').addEventListener('submit', function(e) {
                // Let the form submit normally to trigger file download
                // No need to prevent default or use AJAX for file downloads
            });
    </script>
@endpush
