@extends('reports.layouts.print')

@section('report-title', 'Barangay Analytics Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">🗺️ Overview</h3>
        <div class="summary-item"><span class="summary-label">Barangays:</span> {{ count($data['barangays']) }}</div>
        <div class="summary-item"><span class="summary-label">Total Land Area (ha):</span> {{ number_format(collect($data['barangays'])->sum('total_land_area'), 2) }}</div>
    </div>
@endsection

@section('content')
    <h3>Barangay Statistics</h3>
    <table>
        <thead>
            <tr>
                <th>Barangay</th>
                <th>Farmer Count</th>
                <th>Total Land Area (ha)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['barangays'] as $barangay)
            <tr>
                <td>{{ $barangay->barangay_name }}</td>
                <td>{{ number_format($barangay->farmer_count) }}</td>
                <td>{{ number_format($barangay->total_land_area ?? 0, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
