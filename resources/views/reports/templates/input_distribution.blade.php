@extends('reports.layouts.print')

@section('report-title', 'Input Distribution Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">📦 Distribution Summary</h3>
        <div class="summary-item"><span class="summary-label">Total Distributions:</span> {{ $data['total'] }}</div>
        <div class="summary-item"><span class="summary-label">Total Quantity:</span> {{ number_format($data['totalQuantity']) }} units</div>
    </div>
@endsection

@section('content')
    <h3>Distribution Details</h3>
    <table>
        <thead>
            <tr>
                <th>Farmer</th>
                <th>Barangay</th>
                <th>Input</th>
                <th>Quantity</th>
                <th>Date Given</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['distributions'] as $dist)
            <tr>
                <td>{{ $dist->first_name }} {{ $dist->last_name }}</td>
                <td>{{ $dist->barangay_name }}</td>
                <td>{{ $dist->input_name }}</td>
                <td>{{ number_format($dist->quantity_distributed) }} {{ $dist->unit }}</td>
                <td>{{ \Carbon\Carbon::parse($dist->date_given)->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
