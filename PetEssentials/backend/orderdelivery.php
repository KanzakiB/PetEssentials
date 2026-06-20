<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit();
}

$limit = 10;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max($page, 1); 
$start = ($page - 1) * $limit;

$total_result = mysqli_query($conn, "
    SELECT COUNT(*) AS total 
    FROM orders o
    WHERE o.order_status = 2
");
$total_entries = mysqli_fetch_assoc($total_result)['total'];

$total_pages = ceil($total_entries / $limit);

$query = "
    SELECT 
        o.orderID, 
        o.order_date, 
        o.total_amount, 
        u.fname as firstname, 
        u.lname as lastname, 
        u.email,
        ca.House_no,
        ca.street_name,
        ca.Barangay,
        ca.City,
        ca.Postal_code,
        os.status_name
    FROM 
        orders o
    JOIN 
        registered_users u ON o.registeredID = u.registeredID
    JOIN 
        customer_address ca ON o.addressID = ca.addressID
    JOIN 
        order_status os ON o.order_status = os.statusID
    WHERE 
        o.order_status = 2
    ORDER BY 
        o.order_date DESC
    LIMIT $start, $limit
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out for Delivery Orders</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<style>
.pagination a {
    display: inline-block;
    text-decoration: none;
    padding: 8px 12px;
    margin: 0 5px;
    border: 1px solid #ddd;
    border-radius: 5px;
    color: #555;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background-color: #ffe2fa;
    color: black;
}

.pagination a.bg-pink-500 {
    background-color: #FD9596;
    color: white;
}
</style>

<body class="bg-gray-100">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <main class="flex-1 p-4 sm:p-6 md:p-8 ml-24 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <?php include 'admin_header.php'; ?>

            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mt-10 mb-4 text-pink-800">Out for Delivery Orders</h1>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-purple-200">
                            <thead class="bg-[#FD9596]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Order ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Customer Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Order Date</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Total Amount</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Delivery Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#ffe2fa]">
                                        <td class="px-4 py-2 text-sm text-black">#<?php echo $order['orderID']; ?></td>
                                        <td class="px-4 py-2 text-sm text-black">
                                            <?php echo htmlspecialchars($order['firstname'] . ' ' . $order['lastname']); ?>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-black">
                                            <?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-black">₱<?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td class="px-4 py-2 text-sm text-black">
                                            <?php 
                                            echo htmlspecialchars(
                                                $order['House_no'] . ', ' . 
                                                $order['street_name'] . ', ' . 
                                                $order['Barangay'] . ', ' . 
                                                $order['City'] . ' ' . 
                                                $order['Postal_code']
                                            ); 
                                            ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="pagination mt-4 flex justify-center items-center space-x-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1; ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            &laquo; Previous
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i; ?>" class="px-3 py-1 rounded <?= $i == $page ? 'bg-pink-500 text-white' : 'bg-gray-200 hover:bg-gray-300'; ?>">
                            <?= $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1; ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">
                            Next &raquo;
                        </a>
                    <?php endif; ?>
                </div>            </div>
        </main>
    </div>

    <script>
    function updateOrderStatus(selectElement, orderID) {
        const newStatusID = selectElement.value;
        if (newStatusID) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '';

            const orderIDInput = document.createElement('input');
            orderIDInput.type = 'hidden';
            orderIDInput.name = 'orderID';
            orderIDInput.value = orderID;

            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'new_status';
            statusInput.value = newStatusID;

            form.appendChild(orderIDInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>
</html>