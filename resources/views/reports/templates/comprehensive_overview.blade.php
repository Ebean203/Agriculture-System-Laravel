@extends('reports.layouts.print')

@section('report-title', 'Comprehensive Overview Report')
@section('report-subtitle', 'Lagonglong FARMS')

@section('summary')
    <div class="summary-box">
        <h3 style="margin-top:0;color:#15803d">📈 Key Metrics</h3>
        <div class="summary-item"><span class="summary-label">Total Farmers:</span> {{ $data['farmers']['total'] }}</div>
        <div class="summary-item"><span class="summary-label">Distributions:</span> {{ $data['distributions']['total'] }} ({{ number_format($data['distributions']['totalQuantity']) }} units)</div>
        <div class="summary-item"><span class="summary-label">Total Yield:</span> {{ number_format($data['yields']['total'], 2) }} | Avg: {{ number_format($data['yields']['average'], 2) }}</div>
        <div class="summary-item"><span class="summary-label">Inventory Items:</span> {{ $data['inventory']['totalItems'] }} (Low: {{ $data['inventory']['lowStock']->count() }})</div>
    </div>
@endsection

@section('content')
    <h3>Farmers Overview</h3>
    <table>
        <thead>
            <tr>
                <th>Barangay</th>
                <th>Farmer Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['farmers']['byBarangay'] as $barangay => $count)
            <tr>
                <td>{{ $barangay }}</td>
                <td>{{ $count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Input Distribution Overview</h3>
    <table>
        <thead>
            <tr>
                <th>Input</th>
                <th>Distributions</th>
                <th>Total Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['distributions']['byInput'] as $inputName => $info)
            <tr>
                <td>{{ $inputName }}</td>
                <td>{{ number_format($info['count']) }}</td>
                <td>{{ number_format($info['total_quantity']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Inventory Overview</h3>
    <table>
        <thead>
            <tr>
                <th>Input</th>
                <th>On Hand</th>
                <th>Unit</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['inventory']['inventory'] as $item)
            @php $status = $item->quantity_on_hand < 20 ? 'Low' : 'OK'; @endphp
            <tr>
                <td>{{ $item->input_name }}</td>
                <td>{{ number_format($item->quantity_on_hand) }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection
