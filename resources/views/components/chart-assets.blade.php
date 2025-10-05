{{-- Chart.js Assets - Include this in pages that need charts --}}
<script src="{{ asset('agriculture-assets/js/chart.min.js') }}"></script>
<script src="{{ asset('agriculture-assets/js/chartjs-plugin-datalabels.min.js') }}"></script>
<script>
    // Register the datalabels plugin
    Chart.register(ChartDataLabels);
    
    // Default chart configuration for Agriculture System
    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.position = 'bottom';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 20;
    
    // Agriculture system color palette
    window.agricultureColors = {
        primary: '#16a34a',
        secondary: '#059669',
        accent: '#10b981',
        light: '#dcfce7',
        dark: '#166534',
        blue: '#3b82f6',
        purple: '#8b5cf6',
        cyan: '#06b6d4',
        red: '#ef4444',
        orange: '#f97316',
        yellow: '#eab308'
    };
</script>