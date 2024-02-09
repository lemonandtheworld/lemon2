<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["product_id"])) {
    $product_id = $_GET["product_id"];

    // Retrieve product details from the database using prepared statement
    $get_product_sql = "SELECT * FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($get_product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit();
    }

}

// Handle form submission for updating product details
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["product_id"], $_POST["product_name"], $_POST["quantity_in_stock"], $_POST["price"])) {
        $product_id = $_POST["product_id"];
        $product_name = $_POST["product_name"];
        $quantity_in_stock = $_POST["quantity_in_stock"];
        $price = $_POST["price"];

        // Update product details in the database using prepared statement
        $update_product_sql = "UPDATE products 
                                SET product_name = ?, 
                                    quantity_in_stock = ?, 
                                    price = ? 
                                WHERE product_id = ?";
        $stmt = $conn->prepare($update_product_sql);
        $stmt->bind_param("sidi", $product_name, $quantity_in_stock, $price, $product_id);

        if ($stmt->execute()) {
            echo "Product updated successfully";
            
            // Refresh product data after update
            $result = $conn->query("SELECT * FROM products WHERE product_id = $product_id");
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
            } else {
                echo "Error fetching updated product data.";
            }
        } else {
            echo "Error updating product: " . $conn->error;
        }
    } else {
        echo "Invalid form data submitted.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <style>
        /* Your styling here */
    </style>
</head>
<body>
    <h2>Edit Product</h2>
    <form method="post" action="">
        <input type="hidden" name="product_id" value="<?php echo $product["product_id"]; ?>">
        Product Name: <input type="text" name="product_name" value="<?php echo $product["product_name"]; ?>" required><br>
        Quantity in Stock: <input type="text" name="quantity_in_stock" value="<?php echo $product["quantity_in_stock"]; ?>" required><br>
        Price: <input type="text" name="price" value="<?php echo $product["price"]; ?>" required><br>
        <br><br>
        <input type="submit" value="Save Changes">
    </form>
</body>
</html>
