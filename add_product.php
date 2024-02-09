<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $quantity_in_stock = $_POST["quantity_in_stock"];
    $price = $_POST["price"];

    $sql = "INSERT INTO products (product_name, quantity_in_stock, price) VALUES ('$product_name', $quantity_in_stock, $price)";

    if ($conn->query($sql) === TRUE) {
        echo "Product added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="post" action="">
        Product Name: <input type="text" name="product_name" required><br>
        Quantity in Stock: <input type="number" name="quantity_in_stock" required><br>
        Price: <input type="number" name="price" step="0.01" required><br>
        <input type="submit" value="Add Product">
    </form>
</body>
</html>
