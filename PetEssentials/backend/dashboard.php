<?php
session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit();
}

include 'C:\xampp\htdocs\PetEssentials\connect\connection.php';

// Total Products
$productQuery = "SELECT COUNT(*) as total_products FROM product_item";
$productResult = mysqli_query($conn, $productQuery);
$totalProducts = mysqli_fetch_assoc($productResult)['total_products'];

// Total Users
$userQuery = "SELECT COUNT(*) as total_users FROM registered_users";
$userResult = mysqli_query($conn, $userQuery);
$totalUsers = mysqli_fetch_assoc($userResult)['total_users'];

// Total Items (replaced "Successful Orders" with "Total Products" since no orders table)
$totalItemsQuery = "SELECT COUNT(*) as total_items FROM product_item";
$totalItemsResult = mysqli_query($conn, $totalItemsQuery);
$totalItems = mysqli_fetch_assoc($totalItemsResult)['total_items'];

// Monthly Product Additions (replaced orders chart with product additions)
$monthlyProductsQuery = "SELECT 
    MONTH(date_added) as month, 
    COUNT(*) as product_count
FROM product_item 
WHERE YEAR(date_added) = YEAR(CURRENT_DATE)
GROUP BY MONTH(date_added)
ORDER BY month";
$monthlyProductsResult = mysqli_query($conn, $monthlyProductsQuery);

$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$productCounts = array_fill(0, 12, 0);

while ($row = mysqli_fetch_assoc($monthlyProductsResult)) {
    $monthIndex = $row['month'] - 1;
    $productCounts[$monthIndex] = $row['product_count'];
}

// Low Stock Items (replaced pending orders)
$lowStockQuery = "SELECT 
    itemID, 
    product_name, 
    product_brand, 
    product_price, 
    product_quantity 
FROM product_item 
WHERE product_quantity < 10 
ORDER BY product_quantity ASC 
LIMIT 10";
$lowStockResult = mysqli_query($conn, $lowStockQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 pt-8">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <?php include 'admin_header.php'; ?>
        
        <main class="flex-1 p-4 mt-16 sm:p-6 md:p-6 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <div class="grid sm:grid-cols-1 md:grid-cols-1 lg:grid-cols-3 gap-4 sm:mr-8">
                <div class="bg-white p-3 sm:p-4 rounded-lg shadow">
                    <h2 class="text-sm sm:text-md font-medium">Total Products</h2>
                    <p class="text-xl sm:text-2xl font-bold text-green-500"><?php echo $totalProducts; ?></p>
                </div>

                <div class="bg-white p-3 sm:p-4 rounded-lg shadow">
                    <h2 class="text-sm sm:text-md font-medium">Registered Users</h2>
                    <p class="text-xl sm:text-2xl font-bold text-indigo-500"><?php echo $totalUsers; ?></p>
                </div>

                <div class="bg-white p-3 sm:p-4 rounded-lg shadow">
                    <h2 class="text-sm sm:text-md font-medium">Total Product Items</h2>
                    <p class="text-xl sm:text-2xl font-bold text-red-500"><?php echo $totalItems; ?></p>
                </div>
            </div>

            <!-- Products Overview -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow mt-4 sm:mr-8">
                <h2 class="text-sm sm:text-md font-medium">Products Added Per Month</h2>
                <div class="h-48 sm:h-64 relative">
                    <canvas id="productsChart"></canvas>
                </div>
            </div>

            <!-- Low Stock Items Table -->
            <div class="bg-white p-3 sm:p-4 rounded-lg shadow mt-4 sm:mr-8">
                <h2 class="text-sm sm:text-md font-medium">Low Stock Items (Below 10)</h2>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-sm sm:text-md">Item ID</th>
                                <th class="px-2 py-1 text-sm sm:text-md">Product Name</th>
                                <th class="px-2 py-1 text-sm sm:text-md">Brand</th>
                                <th class="px-2 py-1 text-sm sm:text-md">Price</th>
                                <th class="px-2 py-1 text-sm sm:text-md">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(mysqli_num_rows($lowStockResult) > 0): ?>
                                <?php while($item = mysqli_fetch_assoc($lowStockResult)): ?>
                                    <tr>
                                        <td class="px-2 py-1 text-sm sm:text-md"><?php echo $item['itemID']; ?></td>
                                        <td class="px-2 py-1 text-sm sm:text-md"><?php echo $item['product_name']; ?></td>
                                        <td class="px-2 py-1 text-sm sm:text-md"><?php echo $item['product_brand']; ?></td>
                                        <td class="px-2 py-1 text-sm sm:text-md">$<?php echo number_format($item['product_price'], 2); ?></td>
                                        <td class="px-2 py-1 text-sm sm:text-md <?php echo $item['product_quantity'] <= 0 ? 'text-red-500 font-bold' : 'text-orange-500'; ?>">
                                            <?php echo $item['product_quantity']; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-2 py-4 text-center text-sm text-gray-500">All items are well-stocked!</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
    var ctx = document.getElementById('productsChart').getContext('2d');
    var productsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($monthLabels); ?>,
            datasets: [{
                label: 'Products Added',
                data: <?php echo json_encode($productCounts); ?>,
                backgroundColor: '#ddffdd',
                borderColor: '#00497f',
                borderWidth: 2,
                tension: 0.7 
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Products'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Months'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            }
        }
    });
    </script>
</body>
</html>