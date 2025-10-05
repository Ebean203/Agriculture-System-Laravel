@extends('layouts.agriculture')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="mb-8">
            <i class="fas fa-tools text-6xl text-agri-green mb-4"></i>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Coming Soon</h1>
            <p class="text-xl text-gray-600 mb-4">{{ $pageTitle }} is being converted to Laravel</p>
            <div class="bg-agri-light p-4 rounded-lg">
                <p class="text-agri-dark font-medium">This module will be available in the next conversion step.</p>
            </div>
        </div>
        
        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-agri-green text-white font-medium rounded-lg hover:bg-agri-dark transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection