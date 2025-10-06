<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ trim($__env->yieldContent('report-title')) }} - Lagonglong FARMS</title>
    <link href="{{ asset('agriculture-assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('agriculture-assets/css/fontawesome.min.css') }}" rel="stylesheet">
    <script src="{{ asset('agriculture-assets/js/tailwind-cdn.js') }}"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "agri-green": "#16a34a",
                        "agri-dark": "#16a34a",
                        "agri-light": "#dcfce7"
                    }
                }
            }
        }
    </script>
    <link href="{{ asset('agriculture-assets/css/custom.css') }}" rel="stylesheet">
    <script src="{{ asset('agriculture-assets/js/bootstrap.bundle.min.js') }}"></script>

    <style>
        body { font-family: Arial, sans-serif; margin: 20px; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #16a34a; padding-bottom: 15px; }
        .title { font-size: 28px; font-weight: bold; color: #16a34a; margin-bottom: 10px; }
        .subtitle { font-size: 16px; color: #15803d; margin-bottom: 5px; }
        .report-info { font-size: 14px; color: #6b7280; margin-bottom: 5px; }
        .action-buttons { display: flex; gap: 10px; margin-bottom: 20px; justify-content: center; flex-wrap: wrap; }
        .btn { padding: 12px 24px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .btn-primary { background: linear-gradient(135deg, #16a34a, #15803d); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, #15803d, #166534); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .btn-secondary { background: linear-gradient(135deg, #6b7280, #4b5563); color: white; }
        .btn-secondary:hover { background: linear-gradient(135deg, #4b5563, #374151); transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .save-success { background-color: #dcfce7; color: #166534; border: 2px solid #16a34a; }
        .save-status { text-align: center; padding: 10px; margin: 10px 0; border-radius: 8px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 11px; }
        th { background-color: #16a34a; color: white; padding: 10px 8px; text-align: left; border: 1px solid #ddd; font-weight: bold; font-size: 10px; }
        td { padding: 8px; border: 1px solid #ddd; vertical-align: top; word-wrap: break-word; }
        tr:nth-child(even) { background-color: #dcfce7; }
        .summary-box { background-color: #f0fdf4; border: 2px solid #16a34a; border-radius: 8px; padding: 15px; margin: 20px 0; }
        .summary-item { margin: 8px 0; font-size: 14px; }
        .summary-label { font-weight: bold; color: #15803d; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #6b7280; border-top: 1px solid #d1d5db; padding-top: 10px; }

        @media print {
            .no-print { display: none !important; }
            @page {
                size: A4; margin: 0.75in 0.5in; -webkit-print-color-adjust: exact; print-color-adjust: exact;
            }
            html, body { margin: 0 !important; padding: 0 !important; }
            table { width: 100%; font-size: 8px !important; border-collapse: collapse; }
            th { background-color: #16a34a !important; color: #fff !important; padding: 4px 3px !important; font-size: 7px !important; border: .5px solid #ddd !important; }
            td { padding: 3px 2px !important; font-size: 7px !important; border: .5px solid #ddd !important; }
            tr:nth-child(even) { background-color: #f8f9fa !important; }
            .summary-box { background-color: #f0fdf4 !important; border: 1px solid #16a34a !important; border-radius: 4px; padding: 8px; margin: 8px 0; page-break-inside: avoid; font-size: 9px; }
            .footer { margin-top: 15px; font-size: 7px !important; color: #6b7280 !important; border-top: .5px solid #d1d5db !important; padding-top: 5px; }
        }
    </style>

    @stack('report-head')
</head>
<body>
    <div class="no-print action-buttons">
        <button onclick="printReport()" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
        <button onclick="closeReport()" class="btn btn-secondary"><i class="fas fa-times"></i> Close</button>
    </div>

    <div class="no-print save-status save-success">
        <i class="fas fa-check-circle"></i> This report has been automatically saved to the database
    </div>

    <div class="header">
        <div class="title">@yield('report-title')</div>
        <div class="subtitle">@yield('report-subtitle', 'Lagonglong FARMS')</div>
        <div class="report-info">Report Period: {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}</div>
        <div class="report-info">Generated by: {{ $generatedBy }}</div>
    </div>

    @yield('summary')
    @yield('content')

    <div class="footer">
        <p>Lagonglong FARMS - @yield('report-title')</p>
        <p>This report contains data from {{ $startDate->format('F d, Y') }} to {{ $endDate->format('F d, Y') }}</p>
        <p>Generated on: {{ $generatedAt->format('n/j/y, g:i A') }}</p>
    </div>

    <script>
        function printReport() {
            try {
                document.title = "";
                const style = document.createElement('style');
                style.textContent = `@page{size:A4;margin:0.5in !important}`;
                document.head.appendChild(style);
                setTimeout(() => window.print(), 100);
            } catch(e) { window.print(); }
        }
        function closeReport() { window.close(); }
    </script>

    @stack('report-scripts')
</body>
</html>
