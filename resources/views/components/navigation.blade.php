<nav class="w-full flex items-center justify-end">
    <div class="flex items-center gap-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button onclick="toggleNotificationDropdown()" class="text-agri-green hover:text-agri-dark transition-colors relative">
                <i class="fas fa-bell text-lg"></i>
                <span id="notificationBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">0</span>
            </button>
            <!-- Notification Dropdown positioned to occupy bottom part -->
            <div id="notificationDropdown" class="hidden fixed bg-white rounded-lg shadow-xl border border-gray-200 z-[9999] overflow-hidden" style="top: 70px; right: 20px; bottom: 20px; width: 400px;">
                <div class="p-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Notifications
                        </h3>
                        <span id="notificationCount" class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">0</span>
                    </div>
                </div>
                <div id="notificationList" style="height: calc(100% - 80px); overflow-y: auto;">
                    <!-- Notifications will be loaded here -->
                    <div class="p-4 text-center text-gray-500">
                        <i class="fas fa-spinner fa-spin mb-2"></i>
                        <p class="text-sm">Loading notifications...</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Menu -->
        <div class="flex items-center text-agri-green relative z-[1001]" id="userMenu">
            <button class="flex items-center focus:outline-none" onclick="toggleDropdown()" type="button">
                <i class="fas fa-user-circle text-lg mr-2"></i>
                <span>{{ session('full_name', 'System Administrator') }}</span>
                <i class="fas fa-chevron-down ml-2 text-xs transition-transform duration-200" id="dropdownArrow"></i>
            </button>
            <!-- Dropdown Menu -->
            <div class="absolute right-0 top-full mt-2 w-48 bg-white rounded-md shadow-xl py-1 hidden" id="dropdownMenu" style="z-index: 999999;">
                <div class="px-4 py-2 text-sm text-gray-700 border-b">
                    <div class="font-medium">{{ session('full_name', 'System Administrator') }}</div>
                    <div class="text-xs text-gray-500">{{ session('role', 'Admin') }}</div>
                </div>
                @if(session('role') && strtolower(session('role')) === 'admin')
                    <a href="{{ route('staff.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-user-tie mr-2"></i>Staff
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
// Function to handle dropdown toggle
function toggleDropdown() {
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');
    if (dropdownMenu.classList.contains('hidden')) {
        dropdownMenu.classList.remove('hidden');
        dropdownMenu.classList.add('show');
        dropdownArrow.classList.add('rotate');
    } else {
        dropdownMenu.classList.add('hidden');
        dropdownMenu.classList.remove('show');
        dropdownArrow.classList.remove('rotate');
    }
}

// Function to handle notification dropdown toggle
function toggleNotificationDropdown() {
    if (window.notificationDropdown) {
        window.notificationDropdown.toggle();
    }
}

// Notification dropdown system
window.notificationDropdown = {
    isOpen: false,
    
    toggle: function() {
        const dropdown = document.getElementById('notificationDropdown');
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    },
    
    open: function() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.remove('hidden');
        dropdown.classList.add('show');
        this.isOpen = true;
        this.loadNotifications();
    },
    
    close: function() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.add('hidden');
        dropdown.classList.remove('show');
        this.isOpen = false;
    },
    
    loadNotifications: function() {
        const notificationList = document.getElementById('notificationList');
        
        fetch('{{ route('api.notifications') }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.displayNotifications(data.notifications);
                    this.updateNotificationCount(data.total_count);
                } else {
                    notificationList.innerHTML = `
                        <div class="p-4 text-center">
                            <i class="fas fa-exclamation-triangle text-red-400 mb-2"></i>
                            <p class="text-sm text-gray-500">Error loading notifications</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading notifications:', error);
                notificationList.innerHTML = `
                    <div class="p-4 text-center">
                        <i class="fas fa-exclamation-triangle text-red-400 mb-2"></i>
                        <p class="text-sm text-gray-500">Network error</p>
                    </div>
                `;
            });
    },
    
    displayNotifications: function(notifications) {
        const notificationList = document.getElementById('notificationList');
        
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="p-4 text-center">
                    <i class="fas fa-check-circle text-green-400 text-xl mb-2"></i>
                    <p class="text-sm text-gray-600">No notifications</p>
                </div>
            `;
            return;
        }
        
        let html = '';
        notifications.forEach((notification, index) => {
            const urgencyClass = this.getUrgencyClass(notification.type);
            const iconClass = this.getIconClass(notification.category, notification.type);
            
            const inputId = notification.data && notification.data.input_id ? notification.data.input_id : '';
            const itemName = notification.data && notification.data.item_name ? notification.data.item_name : '';
            const farmerName = notification.data && notification.data.farmer_name ? notification.data.farmer_name : '';
            const farmerId = notification.data && notification.data.farmer_id ? notification.data.farmer_id : '';
            
            html += `
                <div class="notification-item p-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer ${index === notifications.length - 1 ? 'border-b-0' : ''}"
                     data-notification-id="${notification.id}"
                     data-category="${notification.category}"
                     onclick="handleNotificationClick('${notification.id}', '${notification.category}', '${itemName}', '${inputId}', '${farmerName}', '${farmerId}')">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-xs font-medium text-gray-900">${notification.title}</p>
                                <span class="text-xs px-2 py-1 rounded-full ${urgencyClass.badge}">${notification.type.toUpperCase()}</span>
                            </div>
                            <p class="text-xs text-gray-700 leading-relaxed">${notification.message}</p>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-clock mr-1"></i>
                                ${notification.date}
                            </p>
                        </div>
                    </div>
                </div>
            `;
        });
        
        notificationList.innerHTML = html;
    },
    
    getUrgencyClass: function(type) {
        switch(type) {
            case 'urgent':
                return { badge: 'bg-red-100 text-red-800' };
            case 'warning':
                return { badge: 'bg-yellow-100 text-yellow-800' };
            case 'info':
                return { badge: 'bg-green-100 text-green-800' };
            default:
                return { badge: 'bg-gray-100 text-gray-800' };
        }
    },
    
    getIconClass: function(category, type) {
        if (category === 'visitation') {
            return 'fas fa-calendar-check text-green-600';
        } else if (category === 'inventory') {
            if (type === 'urgent') {
                return 'fas fa-exclamation-triangle text-red-600';
            } else {
                return 'fas fa-info-circle text-yellow-600';
            }
        }
        return 'fas fa-info-circle text-blue-600';
    },
    
    updateNotificationBadge: function(count) {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }
    },
    
    updateNotificationCount: function(count) {
        const countEl = document.getElementById('notificationCount');
        if (countEl) {
            countEl.textContent = count;
        }
        this.updateNotificationBadge(count);
    },
    
    startAutoRefresh: function() {
        setInterval(() => {
            fetch('{{ route('api.notifications') }}?count_only=true')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.updateNotificationBadge(data.critical_count || data.unread_count);
                    }
                })
                .catch(error => {
                    console.error('Error refreshing notification count:', error);
                });
        }, 300000); // 5 minutes
    },
    
    init: function() {
        fetch('{{ route('api.notifications') }}?count_only=true')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.updateNotificationBadge(data.critical_count || data.unread_count);
                }
            })
            .catch(error => {
                console.error('Error loading notification count:', error);
            });
        
        this.startAutoRefresh();
    }
};

// Global click handler for notifications
function handleNotificationClick(notificationId, category, itemName, inputId, farmerName, farmerId) {
    console.log('Notification clicked:', {
        notificationId: notificationId,
        category: category,
        itemName: itemName,
        inputId: inputId,
        farmerName: farmerName,
        farmerId: farmerId
    });
    
    window.notificationDropdown.close();
    navigateToNotificationPage(category, itemName, inputId, farmerName, farmerId);
}

function navigateToNotificationPage(category, itemName, inputId, farmerName, farmerId) {
    console.log('Navigating to:', { category, itemName, inputId, farmerName, farmerId });
    
    switch(category) {
        case 'inventory':
            if (inputId && inputId !== '') {
                                        window.location.href = '/inventory?highlight=' + inputId + '&item=' + encodeURIComponent(itemName);
            } else {
                                        window.location.href = '/inventory?search=' + encodeURIComponent(itemName);
            }
            break;
        case 'visitation':
            if (farmerId && farmerId !== '') {
                window.location.href = '{{ route('distributions') }}?farmer_id=' + farmerId + '&farmer=' + encodeURIComponent(farmerName);
            } else if (farmerName && farmerName !== '') {
                window.location.href = '{{ route('distributions') }}?farmer=' + encodeURIComponent(farmerName);
            } else {
                window.location.href = '{{ route('distributions') }}';
            }
            break;
        default:
            window.location.href = '{{ route('dashboard') }}';
    }
}

// Initialize notification system when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (window.notificationDropdown) {
        window.notificationDropdown.init();
    }
});

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    const userMenu = document.getElementById('userMenu');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const dropdownArrow = document.getElementById('dropdownArrow');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBell = document.querySelector('button[onclick="toggleNotificationDropdown()"]');

    // User menu dropdown
    if (!userMenu.contains(event.target) && !dropdownMenu.classList.contains('hidden')) {
        dropdownMenu.classList.add('hidden');
        dropdownMenu.classList.remove('show');
        dropdownArrow.classList.remove('rotate');
    }
    // Notification dropdown
    if (notificationDropdown && !notificationDropdown.classList.contains('hidden') && (!notificationBell.contains(event.target) && !notificationDropdown.contains(event.target))) {
        notificationDropdown.classList.add('hidden');
        notificationDropdown.classList.remove('show');
    }
});
</script>

<style>
/* Custom dropdown styles */
#dropdownMenu {
    z-index: 999999;
    transition: all 0.5s ease-in-out;
    transform-origin: top right;
}

#dropdownMenu.show {
    display: block !important;
    z-index: 999999 !important;
    position: absolute !important;
    top: 100% !important;
    right: 0 !important;
    margin-top: 0.5rem !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4) !important;
    border: 1px solid rgba(0, 0, 0, 0.1) !important;
}

#dropdownMenu.hidden {
    display: none !important;
}

#dropdownArrow.rotate {
    transform: rotate(180deg);
}

/* Notification dropdown styles */
#notificationDropdown {
    animation: slideDown 0.2s ease-out;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

#notificationDropdown.hidden {
    display: none !important;
}

#notificationDropdown.show {
    display: block !important;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-item:hover {
    background-color: #f9fafb;
    transition: background-color 0.2s ease;
}

/* Ensure dropdown is above everything */
#notificationDropdown {
    z-index: 99999 !important;
}
</style>