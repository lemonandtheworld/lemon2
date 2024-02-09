<?php
include 'db_connection.php';

// Function to get product names for the dropdown
function getProductNames($conn) {
    $stmt = $conn->prepare("SELECT product_id, product_name FROM products");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $productNames = array();
    while ($row = $result->fetch_assoc()) {
        $productNames[$row["product_id"]] = $row["product_name"];
    }
    
    return $productNames;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $quantity_sold = $_POST["quantity_sold"];
    $sale_date = date("Y-m-d");

    // Check if there is enough quantity in stock
    $stmt = $conn->prepare("SELECT quantity_in_stock FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_quantity = $row["quantity_in_stock"];

        if ($quantity_sold <= $current_quantity) {
            // Proceed with the sale
            $stmt = $conn->prepare("UPDATE products SET quantity_in_stock = quantity_in_stock - ? WHERE product_id = ?");
            $stmt->bind_param("ii", $quantity_sold, $product_id);
            $stmt->execute();

            // Record the sale
            $stmt = $conn->prepare("INSERT INTO sales (product_id, quantity_sold, sale_date) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $product_id, $quantity_sold, $sale_date);

            if ($stmt->execute()) {
                echo "Sale recorded successfully";
            } else {
                echo "Error recording sale. Please try again.";
                error_log("Error: " . $stmt->error);
            }
        } else {
            echo "Not enough quantity in stock for the sale.";
        }
    } else {
        echo "Product not found.";
    }
}

$productNames = getProductNames($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Sale</title>
</head>
<body>
    <h2>Make Sale</h2>
    <form method="post" action="">
        Product Name:
        <select name="product_id" required>
            <?php
            foreach ($productNames as $id => $name) {
                echo "<option value=\"$id\">$name</option>";
            }
            ?>
        </select><br>
        Quantity Sold: <input type="number" name="quantity_sold" required><br>
        <input type="submit" value="Make Sale">
    </form>
</body>
</html>
