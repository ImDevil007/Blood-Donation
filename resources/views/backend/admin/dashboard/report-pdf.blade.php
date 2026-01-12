<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @media screen {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                margin: 20px;
            }
            .print-button {
                position: fixed;
                top: 20px;
                right: 20px;
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 10px 20px;
                cursor: pointer;
                border-radius: 5px;
                font-size: 14px;
                z-index: 1000;
            }
            .print-button:hover {
                background-color: #c82333;
            }
        }
        @media print {
            body {
                margin: 0;
                padding: 20px;
            }
            .print-button {
                display: none;
            }
        }
        h1 {
            color: #333;
            border-bottom: 2px solid #dc3545;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .period {
            color: #666;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print / Save as PDF</button>
    <h1>{{ $title }}</h1>
    <div class="period"><strong>Period:</strong> {{ $period }}</div>
    <div class="period"><strong>Total Records:</strong> {{ count($data) }}</div>
    
    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($data as $item)
                <tr>
                    @foreach($rowCallback($item) as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}" style="text-align: center;">No records found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        Generated on {{ now()->format('M d, Y H:i:s') }} | Blood Donation Management System
    </div>
</body>
</html>
