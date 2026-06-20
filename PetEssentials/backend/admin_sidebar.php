
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.3);
            border-radius: 4px;
        }
        #product-dropdown, #order-dropdown {
        @apply hover:bg-transparent;
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button id="sidebar-toggle" class="fixed top-4 left-4 z-50 sm:hidden">
        <i class="fas fa-bars text-2xl"></i>
    </button>

    <!-- Wrapper -->
    <div class="flex">
        <!-- Sidebar -->
        <div id="sidebar" class="
            fixed top-0 left-0 h-screen 
            mt-8
            sm:w-16 lg:w-64 
            bg-white 
            text-[#6e0050]
            py-4 
            px-2 
            shadow-lg 
            transition-all 
            duration-300 
            ease-in-out 
            z-40 
            overflow-y-auto 
            sidebar-scroll
        ">

            <!-- Navigation Links -->
            <nav class="space-y-2 mt-8">
                <!-- Dashboard Link -->
                <a href="dashboard.php" class="flex items-center gap-3 py-2 px-3 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">
                    <i class="fas fa-home text-xl"></i>
                    <span class="hidden lg:inline">Dashboard</span>
                </a>

                <a href="usersregistered.php" class="flex items-center gap-3 py-2 px-3 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">
                    <i class="fas fa-users text-xl"></i>
                    <span class="hidden lg:inline">Customer Management</span>
                </a>

                <!-- Product Management -->
                <div class="relative">
                    <button id="product-management" class="
                        flex items-center 
                        gap-3
                        py-2 px-3 
                        rounded-md 
                        text-[#7f0049]
                        hover:bg-[#FD9596] 
                        hover:text-white 
                        transition 
                        w-full 
                        text-left
                    ">
                        <i class="fas fa-boxes text-xl"></i>
                        <span class="hidden lg:inline">Product Management</span>
                    </button>
                    <div id="product-dropdown" class="hidden pl-4 space-y-1 rounded mt-1">
                        <a href="productlist.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Product List</a>
                        <a href="producttype.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Product Type</a>
                        <a href="productcategory.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Product Category</a>
                    </div>
                </div>

                <!-- Order Management -->
                <div class="relative">
                    <button id="order-management" class="
                        flex items-center 
                        gap-3
                        py-2 px-3 
                        rounded-md 
                        text-[#7f0049]
                        hover:bg-[#FD9596]
                        hover:text-white 
                        transition 
                        w-full 
                        text-left
                    ">
                        <i class="fas fa-shopping-cart text-xl"></i>
                        <span class="hidden lg:inline">Order Management</span>
                    </button>
                    <div id="order-dropdown" class="hidden pl-4 space-y-1 rounded mt-1">
                        <a href="orderpending.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Pending</a>
                        <a href="orderdelivery.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Out for Delivery</a>
                        <a href="ordercancelled.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Cancelled</a>
                        <a href="ordersuccessful.php" class="block py-1.5 px-2 rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">Successful</a>
                    </div>
                </div>
            </nav>

<!-- Profile Section -->
<div class="border-t border-gray-200 pt-4 mb-4">
        <div id="admin-profile-details" class="flex items-center px-3 space-x-3 cursor-pointer rounded-md text-[#7f0049] hover:bg-[#FD9596] hover:text-white transition">
            <!-- Profile Picture Placeholder -->
            <div class="w-12 h-12 rounded-full bg-[#7f0049] text-white flex items-center justify-center">
                <span class="text-xl font-bold">
                    <?php 
                    echo strtoupper(substr($_SESSION['admin_email'], 0, 1)); 
                    ?>
                </span>
            </div>

            <!-- Admin Details -->
            <div class="flex-1 hidden lg:block">
                <h3 class="text-sm font-semibold">
                    Admin
                </h3>
                <p class="text-xs text-gray-500">
                    <?php 
                    echo htmlspecialchars($_SESSION['admin_email']); 
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- Admin Profile Details Modal -->
    <div id="admin-profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-96">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold text-[#7f0049]">Admin Profile</h2>
                <button id="close-profile-modal" class="text-gray-500 hover:text-[#7f0049]">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="text-center mb-4">
                <div class="w-24 h-24 rounded-full bg-[#7f0049] text-white flex items-center justify-center mx-auto mb-3">
                    <span class="text-4xl font-bold">
                        <?php echo strtoupper(substr($_SESSION['admin_email'], 0, 1)); ?>
                    </span>
                </div>
                <h3 class="text-xl font-semibold text-[#7f0049]">Admin</h3>
                <p class="text-gray-500">Administrator</p>
            </div>

            <div class="space-y-3">
                <div class="border-b pb-2">
                    <span class="text-sm text-gray-500">Email Address</span>
                    <p class="font-medium text-[#7f0049]">
                        <?php echo htmlspecialchars($_SESSION['admin_email']); ?>
                    </p>
                </div>
                <div class="border-b pb-2">
                    <span class="text-sm text-gray-500">Admin ID</span>
                    <p class="font-medium text-[#7f0049]">
                        <?php echo htmlspecialchars($_SESSION['adminID']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
            <!-- Logout Button -->
            <div class="absolute bottom-4 w-full px-3">
                <button id="logout-button" class="
                    flex items-center 
                    gap-3
                    py-2 px-3 
                    rounded-md 
                    text-[#7f0049]
                    hover:bg-[#FD9596] 
                    hover:text-white 
                    transition 
                    w-full 
                    text-left
                ">
                    <i class="fas fa-sign-out-alt text-[#7f0049] text-xl"></i>
                    <span class="hidden lg:inline">Log Out</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 w-80">
            <h2 class="text-lg font-bold mb-4">Confirm Logout</h2>
            <p class="text-sm mb-6">Are you sure you want to log out?</p>
            <div class="flex justify-end space-x-4">
                <button id="cancel-logout-button" class="py-2 px-4 bg-gray-300 rounded-md hover:bg-[#FD9596] transition">Cancel</button>
                <button id="confirm-logout-button" class="py-2 px-4 bg-pink-600 text-pink-800 rounded-md hover:bg-[#FD9596] transition">Log Out</button>
            </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const productManagement = document.getElementById('product-management');
    const orderManagement = document.getElementById('order-management');
    const productDropdown = document.getElementById('product-dropdown');
    const orderDropdown = document.getElementById('order-dropdown');
    const adminProfileDetailsBtn = document.getElementById('admin-profile-details');
    const adminProfileModal = document.getElementById('admin-profile-modal');
    const closeProfileModalBtn = document.getElementById('close-profile-modal');
    const logoutButton = document.getElementById('logout-button');
    const logoutModal = document.getElementById('logout-modal');
    const cancelLogoutButton = document.getElementById('cancel-logout-button');
    const confirmLogoutButton = document.getElementById('confirm-logout-button');


    productManagement.addEventListener('click', (event) => {
        event.stopPropagation();
        productDropdown.classList.toggle('hidden');
        
        orderDropdown.classList.add('hidden');
    });

    orderManagement.addEventListener('click', (event) => {
        event.stopPropagation();
        orderDropdown.classList.toggle('hidden');
        
        productDropdown.classList.add('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!productManagement.contains(event.target)) {
            productDropdown.classList.add('hidden');
        }
        if (!orderManagement.contains(event.target)) {
            orderDropdown.classList.add('hidden');
        }
    });

    const adminProfileDetailsSection = document.getElementById('admin-profile-details');
    adminProfileDetailsSection.addEventListener('click', () => {
        adminProfileModal.classList.remove('hidden');

    });

    closeProfileModalBtn.addEventListener('click', () => {
        adminProfileModal.classList.add('hidden');
    });

    adminProfileModal.addEventListener('click', (event) => {
        if (event.target === adminProfileModal) {
            adminProfileModal.classList.add('hidden');
        }
    });

    logoutButton.addEventListener('click', () => {
        logoutModal.classList.remove('hidden');
    });

    cancelLogoutButton.addEventListener('click', () => {
        logoutModal.classList.add('hidden');
    });

    confirmLogoutButton.addEventListener('click', () => {
        window.location.href = 'admin_login.php';
    });

});
</script>
</body>
</html>