@extends('reports.layouts.print')

@section('report-title', 'Yield Monitoring Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">🌾 Yield Summary</h3>
        <div class="summary-item"><span class="summary-label">Total Yield:</span> {{ number_format($data['total'], 2) }}</div>
        <div class="summary-item"><span class="summary-label">Average Yield:</span> {{ number_format($data['average'], 2) }}</div>
    </div>
@endsection

@section('content')
    <h3>Yield Records</h3>
    <table>
        <thead>
            <tr>
                <th>Farmer</th>
                <th>Barangay</th>
                <th>Commodity</th>
                <th>Yield Amount</th>
                <th>Season</th>
                <th>Record Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['yields'] as $yield)
            <tr>
                <td>{{ $yield->first_name }} {{ $yield->last_name }}</td>
                <td>{{ $yield->barangay_name }}</td>
                <td>{{ $yield->commodity_name }}</td>
                <td>{{ number_format($yield->yield_amount, 2) }} {{ $yield->unit ?? '' }}</td>
                <td>{{ $yield->season }}</td>
                <td>{{ \Carbon\Carbon::parse($yield->record_date)->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
