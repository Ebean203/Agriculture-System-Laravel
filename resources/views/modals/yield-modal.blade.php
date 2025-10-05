<!-- Yield Recording Modal - Simple Redirect -->
<div id="yieldModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[99999] items-center justify-center">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100">
                <i class="fas fa-clipboard-list text-orange-600 text-2xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Record Yield</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    You are about to record yield monitoring data. This will open the Yield Monitoring page where you can enter farmer yield information.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="window.location.href='{{ route('yield-monitoring') }}'" class="px-4 py-2 bg-orange-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-arrow-right mr-2"></i>Go to Yield Monitoring
                </button>
                <button onclick="closeModal('yieldModal')" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</div>
