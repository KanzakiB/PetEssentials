<?php
include ('C:\XAMPP\htdocs\PetEssentials\connect\connection.php');
session_start();

if (!isset($_SESSION['adminID'])) {
    header("Location: admin_login.php");
    exit();
}

$limit = 10; 
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;


$total_query = "SELECT COUNT(*) as total FROM product_item";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_products = $total_row['total'];

$total_pages = ceil($total_products / $limit);

$sql = "SELECT pi.*, pc.petID, pt.pet_type_name 
        FROM product_item pi 
        LEFT JOIN product_category pc ON pi.categoryID = pc.productID
        LEFT JOIN pet_type pt ON pc.petID = pt.petID
        LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

$pet_type_query = "SELECT petID, pet_type_name FROM pet_type";
$pet_type_result = mysqli_query($conn, $pet_type_query);

if (!$result || !$pet_type_result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-pt-8">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <main class="flex-1 p-4 sm:p-6 md:p-8 ml-24 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <?php include 'admin_header.php'; ?>

            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mt-10 mb-4 text-pink-800">Product List</h1>
                <div class="flex justify-between mb-4">
                    <button id="openAddModal" class="px-4 py-2 bg-[#FD9596] text-white rounded hover:bg-[#ffe2fa] hover:text-black">
                        Add Product
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-purple-200">
                            <thead class="bg-[#FD9596]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Product Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Pet Type</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Price</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Quantity</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#ffe2fa]"> 
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['product_name']; ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['petID']; ?></td>
                                        <td class="px-4 py-2 text-sm text-black">PHP <?= number_format($row['product_price'], 2); ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['product_quantity']; ?></td>
                                        <td class="px-4 py-2">
                                            <button class="text-black hover:underline openEditModal" 
                                                data-id="<?= $row['itemID']; ?>"
                                                data-name="<?= $row['product_name']; ?>"
                                                data-category="<?= $row['categoryID']; ?>"
                                                data-price="<?= $row['product_price']; ?>"
                                                data-quantity="<?= $row['product_quantity']; ?>"
                                                data-description="<?= htmlspecialchars($row['product_description']); ?>" 
                                                >Edit</button> |
                                            <button class="text-red-600 hover:underline deleteProduct" 
                                                data-id="<?= $row['itemID']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination Controls -->
                <div class="mt-4 flex justify-center">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1; ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 mr-2">Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i; ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 mr-2 <?= ($i == $page) ? 'bg-gray-500 text-white' : ''; ?>">
                            <?= $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1; ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Next</a>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
