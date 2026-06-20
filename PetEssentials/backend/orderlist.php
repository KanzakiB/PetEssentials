<?php
include('C:\XAMPP\htdocs\PetEssentials\connection\connection.php');
session_start();
if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit();
}

$sortBy = $_GET['sortBy'] ?? 'latest';
$sqlOrder = "SELECT order_list.order_id, order_list.prod_id, order_list.order_date, order_status.status_name 
             FROM order_list 
             JOIN order_status ON order_list.status_id = order_status.status_id";

$sqlOrder .= ($sortBy === 'status') 
    ? " ORDER BY order_status.status_name ASC" 
    : " ORDER BY order_list.order_date DESC";

$resultOrder = mysqli_query($conn, $sqlOrder);

$resultStatus = mysqli_query($conn, "SELECT * FROM order_status");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-purple-50 pt-8">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <main class="flex-1 p-4 sm:p-6 md:p-8 ml-24 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <?php include 'admin_header.php'; ?>

            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mb-4">Order List</h1>

                <!-- Sort by Dropdown -->
                <div class="flex justify-between items-center mb-4">
                    <label for="sortBy" class="text-sm font-medium text-purple-700">Sort by:</label>
                    <select id="sortBy" class="ml-2 p-2 rounded-lg border border-gray-300" onchange="sortOrders(this.value)">
                        <option value="latest" <?= $sortBy === 'latest' ? 'selected' : '' ?>>Latest</option>
                        <option value="status" <?= $sortBy === 'status' ? 'selected' : '' ?>>Status</option>
                    </select>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-200">
                            <thead class="bg-purple-600">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Order ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Product</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Order Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Status</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($resultOrder)): ?>
                                    <tr class="hover:bg-purple-50">
                                        <td class="px-4 py-2 text-sm text-gray-700"><?= $row['order_id']; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?= $row['prod_id']; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?= $row['order_date']; ?></td>
                                        <td class="px-4 py-2 text-sm text-gray-700"><?= $row['status_name']; ?></td>
                                        <td class="px-4 py-2">
                                            <button class="text-green-500 hover:underline" onclick="openUpdateModal('<?= $row['order_id']; ?>')">Update Status</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Update Status Modal -->
            <div id="updateModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
                <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                    <h2 class="text-xl font-bold mb-4">Update Status</h2>
                    <form id="updateStatusForm" method="POST" action="update_status.php">
                        <input type="hidden" id="updateOrderId" name="order_id">
                        <label for="statusSelect" class="block text-sm font-medium mb-2">Change Status</label>
                        <select id="statusSelect" name="status_id" class="w-full border-gray-300 rounded-lg p-2">
                            <?php while ($status = mysqli_fetch_assoc($resultStatus)): ?>
                                <option value="<?= $status['status_id']; ?>"><?= $status['status_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <div class="mt-4 text-right">
                            <button type="button" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeUpdateModal()">Cancel</button>
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function sortOrders(value) {
            window.location.href = `orderlist.php?sortBy=${value}`;
        }

        function openUpdateModal(orderId) {
            document.getElementById('updateOrderId').value = orderId;
            document.getElementById('updateModal').classList.remove('hidden');
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }
    </script>
</body>

</html>
