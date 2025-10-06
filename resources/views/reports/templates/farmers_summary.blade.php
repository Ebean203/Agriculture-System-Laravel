@extends('reports.layouts.print')

@section('report-title', 'Farmers Summary Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">👥 Summary Statistics</h3>
        <div class="summary-item"><span class="summary-label">Total Farmers Registered:</span> {{ $data['total'] }}</div>
        <div class="summary-item"><span class="summary-label">Barangays Covered:</span> {{ $data['byBarangay']->count() }}</div>
        <div class="summary-item"><span class="summary-label">Primary Commodities:</span> {{ $data['byCommodity']->count() }}</div>
    </div>
@endsection

@section('content')
    <h3>Farmers by Barangay</h3>
    <table>
        <thead>
            <tr>
                <th>Barangay</th>
                <th>Farmer Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['byBarangay'] as $barangay => $count)
            <tr>
                <td>{{ $barangay }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Farmers by Primary Commodity</h3>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Farmer Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['byCommodity'] as $commodity => $count)
            <tr>
                <td>{{ $commodity }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Detailed Farmers List</h3>
    <table>
        <thead>
            <tr>
                <th>Farmer ID</th>
                <th>Name</th>
                <th>Barangay</th>
                <th>Commodities</th>
                <th>Land Area (ha)</th>
                <th>Registration Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['farmers'] as $farmer)
            <tr>
                <td>{{ $farmer->farmer_id }}</td>
                <td>{{ $farmer->first_name }} {{ $farmer->middle_name }} {{ $farmer->last_name }} {{ $farmer->suffix }}</td>
                <td>{{ $farmer->barangay_name }}</td>
                <td>{{ $farmer->commodities ?? 'N/A' }}</td>
                <td>{{ $farmer->land_area_hectares ? number_format($farmer->land_area_hectares, 2) : 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($farmer->registration_date)->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
