<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Farmers Database Export</title>
    <style>
        @page {
            margin: 0.5in;
            size: landscape;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 30px 15px;
            background-color: #16a34a;
            color: white;
            border-radius: 0;
        }
        
        .title {
            font-size: 28pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .subtitle {
            font-size: 16pt;
            font-weight: 600;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        
        .export-info {
            font-size: 10pt;
            margin-top: 10px;
            line-height: 1.6;
        }
        
        .summary-stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            table-layout: fixed;
        }
        
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 20px 15px;
            background-color: #16a34a;
            color: white;
            border: 3px solid white;
            width: 33.33%;
        }
        
        .stat-number {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 10pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 9pt;
            background-color: white;
        }
        
        thead tr {
            background-color: #16a34a;
            color: white;
        }
        
        th {
            padding: 12px 6px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #16a34a;
            white-space: nowrap;
            font-size: 9pt;
        }
        
        td {
            padding: 10px 6px;
            border: 1px solid #ddd;
            font-size: 8.5pt;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        tbody tr:hover {
            background-color: #f0fdf4;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge-yes {
            background-color: #16a34a;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
            white-space: nowrap;
            display: inline-block;
        }
        
        .badge-no {
            background-color: #dc2626;
            color: white;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
            white-space: nowrap;
            display: inline-block;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #16a34a;
            text-align: center;
            font-size: 9pt;
            color: #666;
            background-color: white;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">🌾 LAGONGLONG FARMS</div>
        <div class="subtitle">LIST OF FARMERS</div>
        <div class="export-info">
            Generated on: {{ now()->format('F d, Y g:i A') }}<br>
            Total Records: {{ $farmers->count() }}
        </div>
    </div>
    
    <div class="summary-stats">
        <div class="stat-item">
            <div class="stat-number">{{ $farmers->count() }}</div>
            <div class="stat-label">Total Farmers</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ now()->format('Y') }}</div>
            <div class="stat-label">Export Year</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ now()->format('M d') }}</div>
            <div class="stat-label">Export Date</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Birth Date</th>
                <th>Gender</th>
                <th>Contact</th>
                <th>Barangay</th>
                <th>Civil Status</th>
                <th>Spouse</th>
                <th>Household Size</th>
                <th>Education</th>
                <th>Occupation</th>
                <th>Commodity</th>
                <th>Land Area (Ha)</th>
                <th>Years Farming</th>
                <th>4Ps</th>
                <th>Indigenous</th>
                <th>RSBSA</th>
                <th>NCFRS</th>
                <th>Fisherfolk</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($farmers as $farmer)
            <tr>
                <td class="text-center">{{ $farmer->farmer_id }}</td>
                <td>{{ trim($farmer->first_name . ' ' . $farmer->middle_name . ' ' . $farmer->last_name . ' ' . $farmer->suffix) }}</td>
                <td class="text-center">
                    {{ $farmer->birth_date && $farmer->birth_date != '0000-00-00' ? \Carbon\Carbon::parse($farmer->birth_date)->format('M d, Y') : '-' }}
                </td>
                <td class="text-center">{{ $farmer->gender ?? '-' }}</td>
                <td>{{ $farmer->contact_number ?? '-' }}</td>
                <td>{{ $farmer->barangay->barangay_name ?? '-' }}</td>
                <td class="text-center">{{ $farmer->householdInfo->civil_status ?? '-' }}</td>
                <td>{{ $farmer->householdInfo->spouse_name ?? '-' }}</td>
                <td class="text-center">{{ $farmer->householdInfo->household_size ?? '-' }}</td>
                <td>{{ $farmer->householdInfo->education_level ?? '-' }}</td>
                <td>{{ $farmer->householdInfo->occupation ?? '-' }}</td>
                <td>
                    {{ $farmer->commodities->pluck('commodity_name')->join(', ') ?: '-' }}
                </td>
                <td class="text-right">
                    @if($farmer->land_area_hectares)
                        {{ number_format($farmer->land_area_hectares, 2) }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">
                    @php
                        $yearsTotal = $farmer->commodities->pluck('pivot.years_farming')->filter()->first();
                    @endphp
                    {{ $yearsTotal ?? '-' }}
                </td>
                <td class="text-center">
                    @if($farmer->householdInfo && $farmer->householdInfo->is_member_of_4ps)
                        <span class="badge-yes">Yes</span>
                    @else
                        <span class="badge-no">No</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($farmer->householdInfo && $farmer->householdInfo->is_ip)
                        <span class="badge-yes">Yes</span>
                    @else
                        <span class="badge-no">No</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($farmer->is_rsbsa)
                        <span class="badge-yes">Yes</span>
                    @else
                        <span class="badge-no">No</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($farmer->is_ncfrs)
                        <span class="badge-yes">Yes</span>
                    @else
                        <span class="badge-no">No</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($farmer->is_fisherfolk)
                        <span class="badge-yes">Yes</span>
                    @else
                        <span class="badge-no">No</span>
                    @endif
                </td>
                <td class="text-center">
                    {{ $farmer->registration_date ? \Carbon\Carbon::parse($farmer->registration_date)->format('M d, Y H:i') : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div style="width:100%; margin-top:32px; text-align:center;">
        <hr style="border:0; border-top:1px solid #bdbdbd; margin-bottom:10px;">
        <span style="display:inline-block; background:#e5e7eb; color:#374151; font-size:10px; padding:2px 10px; border-radius:3px; font-family:Arial, sans-serif; font-weight:600;">
            Generated by {{ $generatedBy }}
        </span>
    </div>
</body>
</html>
