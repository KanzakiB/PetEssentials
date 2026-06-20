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

$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM product_category");
$total_entries = mysqli_fetch_assoc($total_result)['total'];

$total_pages = ceil($total_entries / $limit);

$sql = "
    SELECT 
        pc.productID, 
        pt.pet_type_name AS petName, 
        ptype.ptype_name AS productTypeName
    FROM 
        product_category pc
    INNER JOIN 
        pet_type pt ON pc.petID = pt.petID
    INNER JOIN 
        product_type ptype ON pc.ptypeID = ptype.ptypeID
    LIMIT $start, $limit
";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Categories</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
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
                <h1 class="text-2xl font-bold mt-10 mb-4 text-pink-800">Product Categories</h1>
                <div class="flex justify-between mb-4">
                    <button id="openAddModal" class="px-4 py-2 bg-[#FD9596] text-white rounded hover:bg-[#ffe2fa] hover:text-black">
                        Add Category
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-purple-200">
                            <thead class="bg-[#FD9596]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Product ID</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Pet Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Product Type</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#ffe2fa]">
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['productID']; ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['petName']; ?></td>
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['productTypeName']; ?></td>
                                        <td class="px-4 py-2">
                                            <button class="text-black hover:underline openEditModal" 
                                                data-id="<?= $row['productID']; ?>"
                                                data-pet="<?= $row['petName']; ?>"
                                                data-ptype="<?= $row['productTypeName']; ?>"
                                                >Edit</button> |
                                            <button class="text-red-600 hover:underline deleteCategory" 
                                                data-id="<?= $row['productID']; ?>">Delete</button>
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
                </div>
            </div>
        </main>
    </div>
</body>
</html>
