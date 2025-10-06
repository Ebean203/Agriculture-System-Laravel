@extends('reports.layouts.print')

@section('report-title', 'Commodity Production Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">🌱 Overview</h3>
        <div class="summary-item"><span class="summary-label">Commodities:</span> {{ count($data['commodities']) }}</div>
        <div class="summary-item"><span class="summary-label">Total Production:</span> {{ number_format(collect($data['commodities'])->sum('total_production'), 2) }}</div>
    </div>
@endsection

@section('content')
    <h3>Production by Commodity</h3>
    <table>
        <thead>
            <tr>
                <th>Commodity</th>
                <th>Total Production</th>
                <th>Average Production</th>
                <th>Harvest Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['commodities'] as $commodity)
            <tr>
                <td>{{ $commodity->commodity_name }}</td>
                <td>{{ number_format($commodity->total_production, 2) }}</td>
                <td>{{ number_format($commodity->avg_production, 2) }}</td>
                <td>{{ number_format($commodity->harvest_count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
