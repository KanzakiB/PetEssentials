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

$sql = "SELECT * FROM product_type LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$total_sql = "SELECT COUNT(*) FROM product_type";
$total_result = mysqli_query($conn, $total_sql);
$total_row = mysqli_fetch_row($total_result);
$total = $total_row[0];
$total_pages = ceil($total / $limit);



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product_type'])) {
    $ptype_name = $_POST['ptype_name'];

    $insert_product_type_sql = "INSERT INTO product_type (ptype_name) VALUES ('$ptype_name')";
    mysqli_query($conn, $insert_product_type_sql);

}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_product_type'])) {
    $ptypeID = $_POST['ptypeID'];
    $ptype_name = $_POST['ptype_name'];

    $update_product_type_sql = "UPDATE product_type SET ptype_name='$ptype_name' WHERE ptypeID='$ptypeID'";
    mysqli_query($conn, $update_product_type_sql);

}

if (isset($_POST['delete_product_type'])) {
    $ptypeID = $_POST['ptypeID'];

    $delete_product_type_sql = "DELETE FROM product_type WHERE ptypeID='$ptypeID'";
    mysqli_query($conn, $delete_product_type_sql);

}

$sql = "SELECT * FROM product_type";
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
    <title>Product Types</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'admin_sidebar.php'; ?>
        <main class="flex-1 p-4 sm:p-6 md:p-8 ml-24 sm:ml-8 md:ml-16 lg:ml-64 transition-all duration-300">
            <?php include 'admin_header.php'; ?>

            <div class="container mx-auto p-4">
                <h1 class="text-2xl font-bold mt-10 mb-4 text-pink-800">Product Types</h1>
                <div class="flex justify-between mb-4">
                    <button id="openAddModal" class="px-4 py-2 bg-[#FD9596] text-white rounded hover:bg-[#ffe2fa] hover:text-black">
                        Add Product Type
                    </button>
                </div>

                <div class="bg-white rounded-lg shadow">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-purple-200">
                            <thead class="bg-[#FD9596]">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Product Type Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-medium text-white">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#ffe2fa]"> 
                                        <td class="px-4 py-2 text-sm text-black"><?= $row['ptype_name']; ?></td>
                                        <td class="px-4 py-2">
                                            <button class="text-black hover:underline openEditModal" 
                                                data-id="<?= $row['ptypeID']; ?>"
                                                data-name="<?= $row['ptype_name']; ?>"
                                                >Edit</button> |
                                            <button class="text-red-600 hover:underline deleteProductType" 
                                                data-id="<?= $row['ptypeID']; ?>">Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

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

    <div id="addModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <form action="" method="POST">
                <h2 class="text-xl font-bold text-black mb-4">Add New Product Type</h2>
                <input type="hidden" name="add_product_type" value="1">
                <div>
                    <label class="block text-sm font-medium text-black">Product Type Name</label>
                    <input type="text" name="ptype_name" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-[#FD9596] text-black rounded hover:bg-[#ffe2fa] mr-2">Save</button>
                    <button type="button" id="closeAddModal" class="px-4 py-2 bg-[#ffc8c9] text-black rounded hover:bg-[#ffe2fa]">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <form action="" method="POST">
                <h2 class="text-xl font-bold text-black mb-4">Edit Product Type</h2>
                <input type="hidden" name="edit_product_type" value="1">
                <input type="hidden" name="ptypeID" id="editPtypeID">
                <div>
                    <label class="block text-sm font-medium text-black">Product Type Name</label>
                    <input type="text" name="ptype_name" id="editPtypeName" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-[#FD9596] text-black rounded hover:bg-[#ffe2fa] mr-2">Save</button>
                    <button type="button" id="closeEditModal" class="px-4 py-2 bg-[#ffc8c9] text-black rounded hover:bg-[#ffe2fa]">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <form action="" method="POST">
                <h2 class="text-xl font-bold text-black mb-4">Delete Product Type</h2>
                <input type="hidden" name="delete_product_type" value="1">
                <input type="hidden" name="ptypeID" id="deletePtypeID">
                <p class="text-black">Are you sure you want to delete this product type?</p>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-500 mr-2">Delete</button>
                    <button type="button" id="closeDeleteModal" class="px-4 py-2 bg-[#ffc8c9] text-black rounded hover:bg-[#ffe2fa]">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('openAddModal').addEventListener('click', function() {
            document.getElementById('addModal').classList.remove('hidden');
        });

        document.getElementById('closeAddModal').addEventListener('click', function() {
            document.getElementById('addModal').classList.add('hidden');
        });

        document.querySelectorAll('.openEditModal').forEach(function(button) {
            button.addEventListener('click', function() {
                const ptypeID = button.getAttribute('data-id');
                const name = button.getAttribute('data-name');

                document.getElementById('editPtypeID').value = ptypeID;
                document.getElementById('editPtypeName').value = name;
                document.getElementById('editModal').classList.remove('hidden');
            });
        });

        document.getElementById('closeEditModal').addEventListener('click', function() {
            document.getElementById('editModal').classList.add('hidden');
        });

        document.querySelectorAll('.deleteProductType').forEach(function(button) {
            button.addEventListener('click', function() {
                const ptypeID = button.getAttribute('data-id');
                document.getElementById('deletePtypeID').value = ptypeID;
                document.getElementById('deleteModal').classList.remove('hidden');
            });
        });

        document.getElementById('closeDeleteModal').addEventListener('click', function() {
            document.getElementById('deleteModal').classList.add('hidden');
        });
    </script>
</body>
</html>