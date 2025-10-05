<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lagonglong FARMS</title>
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
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full mx-4">
            <!-- Logo and Title -->
            <div class="text-center mb-8">
                <i class="fas fa-seedling text-agri-green text-5xl mb-4"></i>
                <h2 class="text-3xl font-bold text-gray-900">Lagonglong FARMS</h2>
                <p class="text-gray-600">Please sign in to continue</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="username" class="block text-gray-700 text-sm font-bold mb-2">
                            Username
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                            <input type="text" id="username" name="username" required
                                class="appearance-none block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-agri-green focus:border-agri-green"
                                placeholder="Enter your username">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" required
                                class="appearance-none block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-agri-green focus:border-agri-green"
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex items-center justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-agri-green hover:bg-agri-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-agri-green">
                                <i class="fas fa-sign-in-alt me-2 align-middle" aria-hidden="true"></i> Sign In
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-600 text-sm">
                &copy; {{ date('Y') }} Lagonglong FARMS
            </div>
        </div>
    </div>
</body>
</html>