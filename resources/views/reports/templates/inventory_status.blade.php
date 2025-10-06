@extends('reports.layouts.print')

@section('report-title', 'Inventory status Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">📦 Current Inventory Status</h3>
        <div class="summary-item"><span class="summary-label">Report Generated:</span> {{ $generatedAt->format('F d, Y h:i A') }}</div>
        <div class="summary-item"><span class="summary-label">Total Input Categories:</span> {{ $data['totalItems'] }}</div>
    </div>
@endsection

@section('content')
    <h3>📋 Detailed Inventory Status</h3>
    <table>
        <thead>
            <tr>
                <th>Input Name</th>
                <th>Unit</th>
                <th>Current Stock</th>
                <th>Total Distributed</th>
                <th>Stock Status</th>
                <th>Last Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['inventory'] as $item)
            @php
                $statusBg = '#dcfce7'; $statusColor = '#166534'; $statusText = 'Adequate';
                if ($item->quantity_on_hand < 10) { $statusBg = '#fee2e2'; $statusColor = '#991b1b'; $statusText = 'Critical'; }
                elseif ($item->quantity_on_hand < 20) { $statusBg = '#fef3c7'; $statusColor = '#92400e'; $statusText = 'Low'; }
                $totalDistributed = isset($item->total_distributed) ? $item->total_distributed : 0;
            @endphp
            <tr>
                <td>{{ $item->input_name }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ number_format($item->quantity_on_hand) }}</td>
                <td>{{ number_format($totalDistributed) }}</td>
                <td style="background-color: {{ $statusBg }}; color: {{ $statusColor }}; padding: 5px; border-radius: 3px; font-weight: bold;">{{ $statusText }}</td>
                <td>{{ \Carbon\Carbon::parse($item->last_updated)->format('M d, Y h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
