@props(['offlineMode' => true])

@if($offlineMode)
    {{-- Local assets --}}
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
    <script src="{{ asset('agriculture-assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('agriculture-assets/js/bootstrap.bundle.min.js') }}"></script>
@else
    {{-- CDN assets (for when you have internet) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endif