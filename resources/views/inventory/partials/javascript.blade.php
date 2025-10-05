<script>
// Modal functions
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.getElementById(modalId).classList.add('flex');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.getElementById(modalId).classList.remove('flex');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    const modals = ['addStockModal', 'updateStockModal', 'distributeModal', 'addNewInputTypeModal', 'addToExistingModal', 'addNewCommodityModal'];
    modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target === modal) {
            closeModal(modalId);
        }
    });
});

function updateStock(inputId, inputName, currentStock) {
    // Ensure all parameters have safe values
    inputId = inputId || '';
    inputName = inputName || 'Unknown Input';
    currentStock = currentStock || 0;
    
    // Safely set values with null checks
    const updateInputId = document.getElementById('update_input_id');
    const updateInputName = document.getElementById('update_input_name');
    const currentStockField = document.getElementById('current_stock');
    const newQuantityField = document.getElementById('new_quantity');
    
    if (updateInputId) updateInputId.value = inputId;
    if (updateInputName) updateInputName.value = inputName;
    if (currentStockField) currentStockField.value = currentStock;
    if (newQuantityField) newQuantityField.value = currentStock;
    
    openModal('updateStockModal');
}

function distributeInput(inputId, inputName, availableStock) {
    // Ensure all parameters have safe values
    inputId = inputId || '';
    inputName = inputName || 'Unknown Input';
    availableStock = availableStock || 0;
    
    // Safely set hidden input value with null check
    const selectedInputId = document.getElementById('selected_input_id');
    if (selectedInputId) selectedInputId.value = inputId;
    
    // Update display with null checks
    const selectedInputName = document.getElementById('selected_input_name');
    const availableQuantity = document.getElementById('available_quantity');
    const quantityDistributed = document.getElementById('quantity_distributed');
    
    if (selectedInputName) selectedInputName.textContent = inputName;
    if (availableQuantity) availableQuantity.textContent = availableStock;
    if (quantityDistributed) quantityDistributed.max = availableStock;
    
    // Set appropriate icon based on input type
    const inputIcon = document.getElementById('input_icon');
    if (inputIcon) {
        const iconElement = inputIcon.querySelector('i');
        if (iconElement) {
            const inputNameLower = inputName.toLowerCase();
            
            // Reset classes
            inputIcon.className = 'w-10 h-10 rounded-lg flex items-center justify-center mr-3';
            
            if (inputNameLower.includes('seed')) {
                inputIcon.classList.add('bg-green-500');
                iconElement.className = 'fas fa-seedling text-white';
            } else if (inputNameLower.includes('fertilizer')) {
                inputIcon.classList.add('bg-blue-500');
                iconElement.className = 'fas fa-leaf text-white';
            } else if (inputNameLower.includes('pesticide') || inputNameLower.includes('herbicide')) {
                inputIcon.classList.add('bg-yellow-500');
                iconElement.className = 'fas fa-flask text-white';
            } else if (inputNameLower.includes('goat') || inputNameLower.includes('chicken')) {
                inputIcon.classList.add('bg-orange-500');
                iconElement.className = 'fas fa-paw text-white';
            } else if (inputNameLower.includes('tractor') || inputNameLower.includes('shovel') || inputNameLower.includes('sprayer') || inputNameLower.includes('pump')) {
                inputIcon.classList.add('bg-purple-500');
                iconElement.className = 'fas fa-tools text-white';
            } else {
                inputIcon.classList.add('bg-gray-500');
                iconElement.className = 'fas fa-box text-white';
            }
        }
    }
    
    // Set default visitation date (7 days after distribution)
    const dateGivenField = document.querySelector('input[name="date_given"]');
    const visitationDate = document.getElementById('visitation_date');
    const distributionDate = (dateGivenField && dateGivenField.value) ? dateGivenField.value : new Date().toISOString().split('T')[0];
    const visitationDateObj = new Date(distributionDate);
    visitationDateObj.setDate(visitationDateObj.getDate() + 7);
    if (visitationDate) {
        visitationDate.value = visitationDateObj.toISOString().split('T')[0];
    }
    
    openModal('distributeModal');
}

// Farmer autocomplete functionality
let farmers = [];
let selectedFarmerIndex = -1;

// Load farmers data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadFarmers();
});

function loadFarmers() {
    fetch('/inventory/farmers')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Ensure data is an array, even if empty
            farmers = Array.isArray(data) ? data : [];
        })
        .catch(error => {
            console.error('Error loading farmers:', error);
            farmers = []; // Set to empty array on error
        });
}

function searchFarmers(query) {
    const suggestions = document.getElementById('farmer_suggestions');
    const selectedFarmerField = document.getElementById('selected_farmer_id');
    
    if (!suggestions) return; // Exit if element doesn't exist
    
    if (!query || query.length < 1) {
        suggestions.innerHTML = '';
        suggestions.classList.add('hidden');
        if (selectedFarmerField) selectedFarmerField.value = '';
        return;
    }

    // Ensure farmers array exists and is not empty
    if (!Array.isArray(farmers) || farmers.length === 0) {
        suggestions.innerHTML = '<div class="px-3 py-2 text-orange-500">No farmers registered yet. Please register farmers first.</div>';
        suggestions.classList.remove('hidden');
        return;
    }

    const filteredFarmers = farmers.filter(farmer => {
        if (!farmer || !farmer.first_name || !farmer.last_name) return false;
        const fullName = `${farmer.first_name} ${farmer.last_name}`.toLowerCase();
        return fullName.includes(query.toLowerCase());
    });

    if (filteredFarmers.length > 0) {
        // Check for exact match and auto-select if found
        const exactMatch = filteredFarmers.find(farmer => {
            const fullName = `${farmer.first_name} ${farmer.last_name}`.toLowerCase();
            return fullName === query.toLowerCase();
        });
        
        if (exactMatch) {
            const selectedFarmerField = document.getElementById('selected_farmer_id');
            if (selectedFarmerField) {
                selectedFarmerField.value = exactMatch.farmer_id;
            }
        }
        
        let html = '';
        filteredFarmers.forEach((farmer, index) => {
            const fullName = `${farmer.first_name || ''} ${farmer.last_name || ''}`.trim();
            const farmerId = farmer.farmer_id || '';
            // Escape quotes and special characters to prevent JavaScript errors
            const escapedName = fullName.replace(/'/g, "\\'").replace(/"/g, '\\"');
            html += `<div class="suggestion-item px-3 py-2 hover:bg-gray-100 cursor-pointer border-b border-gray-100" 
                          onclick="selectFarmer('${farmerId}', '${escapedName}')"
                          data-farmer-id="${farmerId}"
                          data-farmer-name="${escapedName}">
                         <div class="font-medium text-gray-900">${fullName}</div>
                         <div class="text-sm text-gray-500">ID: ${farmerId}</div>
                     </div>`;
        });
        suggestions.innerHTML = html;
        suggestions.classList.remove('hidden');
    } else {
        // Clear selected farmer if no matches found
        const selectedFarmerField = document.getElementById('selected_farmer_id');
        if (selectedFarmerField) {
            selectedFarmerField.value = '';
        }
        suggestions.innerHTML = '<div class="px-3 py-2 text-gray-500">No farmers found matching your search</div>';
        suggestions.classList.remove('hidden');
    }
}

function selectFarmer(farmerId, farmerName) {
    const farmerNameField = document.getElementById('farmer_name');
    const selectedFarmerField = document.getElementById('selected_farmer_id');
    const suggestions = document.getElementById('farmer_suggestions');
    
    if (farmerNameField) {
        farmerNameField.value = farmerName || '';
    }
    
    if (selectedFarmerField) {
        selectedFarmerField.value = farmerId || '';
    }
    
    if (suggestions) {
        suggestions.classList.add('hidden');
    }
}

function showSuggestions() {
    const query = document.getElementById('farmer_name').value;
    if (query.length > 0) {
        searchFarmers(query);
    }
}

function hideSuggestions() {
    // Delay hiding to allow click events on suggestions
    setTimeout(() => {
        const suggestions = document.getElementById('farmer_suggestions');
        if (suggestions) {
            suggestions.classList.add('hidden');
        }
    }, 300);
}

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    // Update Stock Form
    const updateStockForm = document.getElementById('updateStockForm');
    if (updateStockForm) {
        updateStockForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(updateStockForm);
            
            fetch('/inventory/update-stock', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeModal('updateStockModal');
                    location.reload();
                } else {
                    showErrorMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error updating stock');
            });
        });
    }

    // Distribute Form
    const distributeForm = document.getElementById('distributeForm');
    if (distributeForm) {
        distributeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateDistributeForm()) {
                return false;
            }
            
            const formData = new FormData(distributeForm);
            
            fetch('/inventory/distribute', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeModal('distributeModal');
                    location.reload();
                } else {
                    showErrorMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error distributing input');
            });
        });
    }

    // Add New Input Type Form
    const addNewInputForm = document.getElementById('addNewInputForm');
    if (addNewInputForm) {
        addNewInputForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(addNewInputForm);
            
            fetch('/inventory/add-new-input', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeAddNewInputTypeModal();
                    location.reload();
                } else {
                    showErrorMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error adding new input type');
            });
        });
    }

    // Add to Existing Form
    const addToExistingForm = document.getElementById('addToExistingForm');
    if (addToExistingForm) {
        addToExistingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(addToExistingForm);
            
            fetch('/inventory/add-stock', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message);
                    closeAddToExistingModal();
                    location.reload();
                } else {
                    showErrorMessage(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error adding stock');
            });
        });
    }

    // Initialize button event listeners
    const distributeButtons = document.querySelectorAll('.distribute-btn');
    const updateButtons = document.querySelectorAll('.update-btn');
    
    distributeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            const inputName = this.getAttribute('data-input-name');
            const quantity = this.getAttribute('data-quantity');
            distributeInput(inputId, inputName, quantity);
        });
    });
    
    updateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            const inputName = this.getAttribute('data-input-name');
            const quantity = this.getAttribute('data-quantity');
            updateStock(inputId, inputName, quantity);
        });
    });
});

// Form validation functions
function validateDistributeForm() {
    const inputId = document.getElementById('selected_input_id');
    const farmerId = document.getElementById('selected_farmer_id');
    const quantity = document.getElementById('quantity_distributed');
    const dateGiven = document.querySelector('input[name="date_given"]');
    const visitationDate = document.getElementById('visitation_date');
    
    // Check if input is selected
    if (!inputId || !inputId.value) {
        alert('Please select an input to distribute.');
        return false;
    }
    
    // Check if farmer is selected
    if (!farmerId || !farmerId.value) {
        alert('Please select a farmer.');
        return false;
    }
    
    // Check quantity
    if (!quantity || !quantity.value || parseInt(quantity.value) <= 0) {
        alert('Please enter a valid quantity to distribute.');
        return false;
    }
    
    // Check if quantity exceeds available stock
    const maxQuantity = parseInt(quantity.max) || 0;
    if (parseInt(quantity.value) > maxQuantity) {
        alert(`Quantity cannot exceed available stock (${maxQuantity}).`);
        return false;
    }
    
    // Check date given
    if (!dateGiven || !dateGiven.value) {
        alert('Please select a distribution date.');
        return false;
    }
    
    // Check visitation date - now required for all inputs
    if (!visitationDate || !visitationDate.value) {
        alert('Visitation date is required for all input distributions.');
        return false;
    }
    
    // Validate that visitation date is not before distribution date
    if (dateGiven && dateGiven.value && visitationDate.value) {
        const distributionDate = new Date(dateGiven.value);
        const visitDate = new Date(visitationDate.value);
        
        if (visitDate < distributionDate) {
            alert('Visitation date cannot be before the distribution date.');
            return false;
        }
    }
    
    return true;
}

// Message functions
function showSuccessMessage(message) {
    const messageContainer = document.getElementById('messageContainer');
    const successMessage = document.getElementById('successMessage');
    const successText = document.getElementById('successText');
    
    successText.textContent = ' ' + message;
    successMessage.style.display = 'flex';
    messageContainer.style.display = 'block';
    
    // Scroll to top to show message
    window.scrollTo({ top: 0, behavior: 'smooth' });
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeMessage('successMessage');
    }, 5000);
}

function showErrorMessage(message) {
    const messageContainer = document.getElementById('messageContainer');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');
    
    errorText.textContent = ' ' + message;
    errorMessage.style.display = 'flex';
    messageContainer.style.display = 'block';
    
    // Scroll to top to show message
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function closeMessage(messageId) {
    const message = document.getElementById(messageId);
    const messageContainer = document.getElementById('messageContainer');
    
    message.style.display = 'none';
    
    // Hide container if no messages are visible
    const successVisible = document.getElementById('successMessage').style.display !== 'none';
    const errorVisible = document.getElementById('errorMessage').style.display !== 'none';
    
    if (!successVisible && !errorVisible) {
        messageContainer.style.display = 'none';
    }
}

// Dropdown functions
function toggleAddInputDropdown() {
    const dropdown = document.getElementById('addInputDropdown');
    const arrow = document.getElementById('addInputArrow');
    dropdown.classList.toggle('hidden');
    arrow.classList.toggle('rotate-180');
}

// Modal management functions
function openAddNewInputTypeModal() {
    document.getElementById('addNewInputTypeModal').classList.remove('hidden');
}

function closeAddNewInputTypeModal() {
    document.getElementById('addNewInputTypeModal').classList.add('hidden');
}

function openAddToExistingModal() {
    document.getElementById('addToExistingModal').classList.remove('hidden');
}

function closeAddToExistingModal() {
    document.getElementById('addToExistingModal').classList.add('hidden');
}

function openAddNewCommodityModal() {
    document.getElementById('addNewCommodityModal').classList.remove('hidden');
}

function closeAddNewCommodityModal() {
    document.getElementById('addNewCommodityModal').classList.add('hidden');
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('addInputDropdown');
    const button = event.target.closest('[onclick="toggleAddInputDropdown()"]');
    
    if (!button && dropdown && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
        const arrow = document.getElementById('addInputArrow');
        if (arrow) arrow.classList.remove('rotate-180');
    }
});
</script>