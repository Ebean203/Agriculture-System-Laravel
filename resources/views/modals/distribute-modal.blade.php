<!-- Distribute Input Modal - Simple Redirect -->
<div id="distributeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-[99999] items-center justify-center">
    <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                <i class="fas fa-truck text-blue-600 text-2xl"></i>
            </div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Distribute Inputs</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    You are about to distribute agricultural inputs to farmers. This will open the Inventory Management page where you can select the input and farmer.
                </p>
            </div>
            <div class="items-center px-4 py-3">
                <button onclick="window.location.href='/inventory'" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-arrow-right mr-2"></i>Go to Inventory Page
                </button>
                <button onclick="closeModal('distributeModal')" class="mt-3 px-4 py-2 bg-gray-100 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</div>
