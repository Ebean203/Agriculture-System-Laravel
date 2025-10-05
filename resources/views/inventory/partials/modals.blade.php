<!-- Add Stock Modal -->
<div id="addStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle text-agri-green mr-2"></i>Add Stock
            </h3>
            <p class="text-sm text-gray-600 mt-1">Add new stock to existing inventory</p>
        </div>
        <form id="addStockForm">
            @csrf
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Input Item</label>
                    <select name="input_id" id="add_input_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-agri-green" required>
                        <option value="">Select an input</option>
                        @foreach($inputs as $input)
                            <option value="{{ $input->input_id }}">{{ $input->input_name }} ({{ $input->unit }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add</label>
                    <input type="number" name="quantity_to_add" id="add_quantity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-agri-green" required>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('addStockModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-agri-green text-white rounded-lg hover:bg-agri-dark transition-colors">Add Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-edit text-agri-green mr-2"></i>Update Stock Level
            </h3>
            <p class="text-sm text-gray-600 mt-1">Correct the current stock quantity (inventory adjustment only)</p>
        </div>
        <form id="updateStockForm">
            @csrf
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                    <input type="text" id="update_input_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <input type="text" id="current_stock" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Quantity</label>
                    <input type="number" name="new_quantity" id="new_quantity" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-agri-green focus:border-agri-green" required>
                </div>
                <input type="hidden" name="input_id" id="update_input_id">
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3 rounded-b-lg">
                <button type="button" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('updateStockModal')">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-agri-green text-white rounded-lg hover:bg-agri-dark transition-colors">Update Stock</button>
            </div>
        </form>
    </div>
</div>

<!-- Distribute Modal -->
<div id="distributeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-5xl max-h-[95vh] overflow-y-auto">
        <div class="px-6 py-4 border-b border-gray-200 sticky top-0 bg-white z-10">
            <h3 class="text-xl font-semibold text-gray-900 flex items-center">
                <i class="fas fa-share-square text-agri-green mr-3"></i>Distribute Agricultural Input
            </h3>
            <p class="text-sm text-gray-600 mt-1">Manage input distribution and tracking</p>
        </div>
        <form id="distributeForm">
            @csrf
            <div class="px-6 py-6">
                <!-- Hidden input for selected input ID -->
                <input type="hidden" name="input_id" id="selected_input_id">
                
                <!-- Selected Input Display -->
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                    <div class="flex items-center">
                        <div id="input_icon" class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-box text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-blue-900">Selected Input: <span id="selected_input_name">-</span></h4>
                            <p class="text-blue-700">Available Stock: <span id="available_quantity" class="font-medium">0</span></p>
                        </div>
                    </div>
                </div>
                
                <!-- Main form content -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column: Distribution Details -->
                    <div>
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Distribution Details</h5>
                        
                        <!-- Farmer Selection -->
                        <div class="mb-4 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select Farmer <span class="text-red-500">*</span></label>
                            <input type="text" 
                                   id="farmer_name" 
                                   placeholder="Type farmer name to search..." 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   autocomplete="off" 
                                   oninput="searchFarmers(this.value)" 
                                   onfocus="showSuggestions()" 
                                   onblur="hideSuggestions()">
                            <input type="hidden" id="selected_farmer_id" name="farmer_id">
                            
                            <div id="farmer_suggestions" class="absolute z-20 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-y-auto shadow-lg hidden">
                                <!-- Farmer suggestions will be populated here -->
                            </div>
                        </div>
                        
                        <!-- Quantity -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Distribute <span class="text-red-500">*</span></label>
                            <input type="number" 
                                   name="quantity_distributed" 
                                   id="quantity_distributed" 
                                   min="1" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   required>
                        </div>
                        
                        <!-- Date Given -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Given <span class="text-red-500">*</span></label>
                            <input type="date" 
                                   name="date_given" 
                                   id="date_given" 
                                   value="{{ date('Y-m-d') }}" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                   required>
                        </div>
                    </div>
                    
                    <!-- Right Column: Additional Information -->
                    <div>
                        <h5 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h5>
                        
                        <!-- Notes -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                            <textarea name="notes" 
                                      rows="3" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                                      placeholder="Any additional notes about this distribution..."></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Visitation Tracking Section -->
                <div id="visitation_section" class="border-t border-gray-200 pt-6 mt-6">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-orange-600"></i>
                        </div>
                        <h5 class="text-lg font-medium text-gray-900">Visitation Schedule</h5>
                        <span id="visitation_indicator" class="ml-2 px-2 py-1 bg-orange-100 text-orange-800 text-xs font-medium rounded-full">Required</span>
                    </div>
                    
                    <div class="bg-orange-50 border-l-4 border-orange-400 p-4 rounded-r-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-orange-600 mt-0.5 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm text-orange-800 mb-3">
                                    A follow-up visitation is required for all input distributions to ensure proper usage and effectiveness.
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-orange-800 mb-2">Scheduled Visitation Date <span class="text-red-500">*</span></label>
                                        <input type="date" 
                                               name="visitation_date" 
                                               id="visitation_date" 
                                               class="w-full px-3 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 bg-white" 
                                               required>
                                        <p class="text-xs text-orange-700 mt-1">Recommended: 7-14 days after distribution</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-4 rounded-b-lg sticky bottom-0 border-t border-gray-200 z-10">
                <button type="button" class="px-6 py-3 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors font-medium" onclick="closeModal('distributeModal')">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="fas fa-share-square mr-2"></i>Distribute Input
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Input Type Modal -->
<div id="addNewInputTypeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Add New Input Type</h3>
        </div>
        <form id="addNewInputForm">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <label for="input_name" class="block text-sm font-medium text-gray-700 mb-2">Input Name</label>
                    <input type="text" name="input_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g. Rice Seeds, Urea Fertilizer">
                </div>
                
                <div class="mb-4">
                    <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                    <select name="unit" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Unit</option>
                        <option value="kg">Kilogram (kg)</option>
                        <option value="sack">Sack</option>
                        <option value="pack">Pack</option>
                        <option value="liter">Liter</option>
                        <option value="piece">Piece</option>
                        <option value="bottle">Bottle</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="initial_quantity" class="block text-sm font-medium text-gray-700 mb-2">Initial Quantity</label>
                    <input type="number" name="quantity_on_hand" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="0">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddNewInputTypeModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Add Input Type</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add to Existing Input Type Modal -->
<div id="addToExistingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Add Stock to Existing Input</h3>
        </div>
        <form id="addToExistingForm">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Input Type</label>
                    <select name="input_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select an input type</option>
                        @foreach($inputs as $input)
                            <option value="{{ $input->input_id }}">{{ $input->input_name }} ({{ $input->unit }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity to Add</label>
                    <input type="number" name="add_quantity" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter quantity">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddToExistingModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Add Stock</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Add New Commodity Modal -->
<div id="addNewCommodityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Add New Commodity</h3>
            <p class="text-sm text-gray-600 mt-1">Create a new agricultural commodity type</p>
        </div>
        <form id="addNewCommodityForm">
            @csrf
            <div class="p-6">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Commodity Name</label>
                    <input type="text" name="commodity_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g. Rice, Corn, Vegetables">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">Select Category</option>
                        <option value="1">Crops</option>
                        <option value="2">Livestock</option>
                        <option value="3">Fisheries</option>
                        <option value="4">Others</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeAddNewCommodityModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Add Commodity</button>
                </div>
            </div>
        </form>
    </div>
</div>