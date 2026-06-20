<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');

session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit();
}

$limit = 10; 
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT r.*, a.* 
        FROM registered_users r
        LEFT JOIN customer_address a ON r.registeredID = a.registeredID
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$total_sql = "SELECT COUNT(*) FROM registered_users";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total = $total_row[0];
$total_pages = ceil($total / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Users</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <main class="flex-1 p-4 sm:p-6 md:p-8 ml-24 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <?php include 'admin_header.php'; ?>

            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mt-10 mb-4 text-pink-800">Registered Users</h1>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-purple-200">
                            <thead class="bg-[#FD9596]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Username</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Full Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Email</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Phone</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#ffe2fa]"> 
                                        <td class="px-4 py-2 text-sm text-black"><?= htmlspecialchars($row['username']); ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= htmlspecialchars($row['fname'] . ' ' . $row['lname']); ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= htmlspecialchars($row['email']); ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= htmlspecialchars($row['phone_no']); ?></td>
                                        <td class="px-4 py-2">
                                        <button class="view-details text-black hover:underline" 
                                            data-registeredid="<?= htmlspecialchars($row['registeredID']); ?>"
                                            data-username="<?= htmlspecialchars($row['username']); ?>"
                                            data-fname="<?= htmlspecialchars($row['fname']); ?>"
                                            data-lname="<?= htmlspecialchars($row['lname']); ?>"
                                            data-email="<?= htmlspecialchars($row['email']); ?>"
                                            data-phone="<?= htmlspecialchars($row['phone_no']); ?>"
                                            data-profile="<?= htmlspecialchars($row['profile_pic'] ?? 'default-profile.png'); ?>"
                                            data-addressfullname="<?= htmlspecialchars($row['fullname'] ?? ''); ?>"
                                            data-addressphone="<?= htmlspecialchars($row['phone_no'] ?? ''); ?>"
                                            data-house="<?= htmlspecialchars($row['House_no'] ?? ''); ?>"
                                            data-street="<?= htmlspecialchars($row['street_name'] ?? ''); ?>"
                                            data-barangay="<?= htmlspecialchars($row['Barangay'] ?? ''); ?>"
                                            data-city="<?= htmlspecialchars($row['City'] ?? ''); ?>"
                                            data-postal="<?= htmlspecialchars($row['Postal_code'] ?? ''); ?>"
                                        >View Details</button>                                        
                                    </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-6">
                    <nav>
                        <ul class="flex space-x-2">
                            <?php if ($page > 1): ?>
                                <li>
                                    <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 bg-[#FD9596] text-white rounded hover:bg-[#ffe2fa]">Previous</a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <span class="px-4 py-2 bg-gray-300 text-white rounded">Previous</span>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li>
                                    <a href="?page=<?= $i ?>" class="px-4 py-2 <?= ($i == $page) ? 'bg-[#FD9596] text-white' : 'bg-white text-black hover:bg-[#ffe2fa]' ?> rounded"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <li>
                                    <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 bg-[#FD9596] text-white rounded hover:bg-[#ffe2fa]">Next</a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <span class="px-4 py-2 bg-gray-300 text-white rounded">Next</span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </main>
    </div>

<div id="userDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl relative">
        <button id="closeModalBtn" class="absolute top-4 right-4 text-gray-600 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="p-6">
            <div class="flex mb-6">
                <div class="w-48 h-48 rounded-full overflow-hidden mr-6 border-4 border-[#FD9596] p-1">
                    <img id="modalProfilePic" src="" alt="Profile" class="w-full h-full object-cover rounded-full">
                </div>

                <div class="grid grid-cols-2 gap-4 flex-grow">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID</label>
                        <p id="modalRegisteredID" class="text-lg font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">First Name</label>
                        <p id="modalFirstName" class="text-lg font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Last Name</label>
                        <p id="modalLastName" class="text-lg font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Username</label>
                        <p id="modalUsername" class="text-lg font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p id="modalEmail" class="text-lg font-semibold"></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone No.</label>
                        <p id="modalPhoneNo" class="text-lg font-semibold"></p>
                    </div>
                </div>
            </div>

            <!-- Addresses Section -->
            <div>
                <h4 class="text-xl font-bold mb-4">Addresses</h4>
                
                <!-- Addresses Table -->
                <div class="overflow-x-auto max-h-64 overflow-y-auto">
                    <table class="w-full border-collapse">
                        <tbody id="addressesTableBody">
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.view-details').forEach(button => {
    button.addEventListener('click', function() {
        document.getElementById('modalRegisteredID').innerText = this.dataset.registeredid || 'N/A';
        document.getElementById('modalFirstName').innerText = this.dataset.fname || 'N/A';
        document.getElementById('modalLastName').innerText = this.dataset.lname || 'N/A';
        document.getElementById('modalUsername').innerText = this.dataset.username || 'N/A';
        document.getElementById('modalEmail').innerText = this.dataset.email || 'N/A';
        document.getElementById('modalPhoneNo').innerText = this.dataset.phone || 'N/A';
        
        const profilePic = this.dataset.profile || 'default-profile.png';
        document.getElementById('modalProfilePic').src = `../uploads/profile/${profilePic}`;

        const addressesTableBody = document.getElementById('addressesTableBody');
        addressesTableBody.innerHTML = ''; // Clear previous addresses

        function createAddressRow(fullname, phone_no, house_no, street_name, barangay, city, postal_code) {
                const addressComponents = [
                    house_no, 
                    street_name, 
                    barangay, 
                    city, 
                    postal_code
                ].filter(component => component && component.trim() !== '');

                const formattedAddress = addressComponents.join(', ');

            const addressRow = document.createElement('tr');
            addressRow.innerHTML = `
                <td class="p-2">
                    <div class="font-semibold">
                        ${fullname ? fullname : 'No Contact Name'} | 
                        ${phone_no ? phone_no : 'No Contact Number'}
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        ${formattedAddress || 'No Address Provided'}
                    </div>
                </td>
            `;
            return addressRow;
        }

        const addressRow = createAddressRow(
            this.dataset.addressfullname, 
            this.dataset.addressphone,
            this.dataset.house, 
            this.dataset.street, 
            this.dataset.barangay, 
            this.dataset.city, 
            this.dataset.postal
        );
        addressesTableBody.appendChild(addressRow);

        document.getElementById('userDetailsModal').classList.remove('hidden');
    });
});

document.getElementById('closeModalBtn').addEventListener('click', function() {
    document.getElementById('userDetailsModal').classList.add('hidden');
});

document.getElementById('userDetailsModal').addEventListener('click', function(event) {
    if (event.target === this) {
        this.classList.add('hidden');
    }
});
</script></body>
</html>