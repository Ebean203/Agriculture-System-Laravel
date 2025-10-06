@extends('reports.layouts.print')

@section('report-title', 'Registration Analytics Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">📋 Overview</h3>
        <div class="summary-item"><span class="summary-label">Total Days:</span> {{ count($data['registrations']) }}</div>
        <div class="summary-item"><span class="summary-label">Total Registrations:</span> {{ collect($data['registrations'])->sum('count') }}</div>
    </div>
@endsection

@section('content')
    <h3>Daily Registration Trends</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Registrations</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['registrations'] as $reg)
            <tr>
                <td>{{ \Carbon\Carbon::parse($reg->date)->format('M d, Y') }}</td>
                <td>{{ number_format($reg->count) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
