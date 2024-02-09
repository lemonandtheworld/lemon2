<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])) {
    $product_id = $_GET["id"];

    // Retrieve product details from the database
    $get_product_sql = "SELECT * FROM products WHERE product_id = $product_id";
    $result = $conn->query($get_product_sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["edit"])) {
        // Handle edit action
        header("Location: edit_p.php?id=".$product["product_id"]);
        exit();
    } elseif (isset($_POST["delete"])) {
        // Handle delete action
        $product_id = $_POST["product_id"];
        $delete_product_sql = "DELETE FROM products WHERE product_id = $product_id";

        if ($conn->query($delete_product_sql) === TRUE) {
            echo "Product deleted successfully";
        } else {
            echo "Error deleting product: " . $conn->error;
        }
    }
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
        }

        .dropdown {
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <h2>View Products</h2>
    <table border="1">
        <tr>
            <th>Product ID</th>
            <th>Product Name</th>
            <th>Quantity in Stock</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
        <?php
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["product_id"]."</td>
                    <td>".$row["product_name"]."</td>
                    <td>".$row["quantity_in_stock"]."</td>
                    <td>".$row["price"]."</td>
                    <td class='dropdown'>
                        <button class='dropbtn'>Actions</button>
                        <div class='dropdown-content'>
                        <a href='edit_p.php?product_id=".$row["product_id"]."'>Edit</a>

                            <form method='post' action=''>
                                <input type='hidden' name='product_id' value='".$row["product_id"]."'>
                                <input type='submit' name='delete' value='Delete' onclick='return confirmDelete();'>
                            </form>
                        </div>
                    </td>
                </tr>";
        }
        ?>
    </table>

    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this product? This action cannot be undone.');
        }
    </script>
</body>
</html>
