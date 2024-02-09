<?php
include 'db_connection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_id"])) {
    $product_id = $_POST["product_id"];
    $product_name = $_POST["product_name"];
    $quantity_in_stock = $_POST["quantity_in_stock"];
    $price = $_POST["price"];

    $update_product_sql = "UPDATE products 
                            SET product_name = ?, 
                                quantity_in_stock = ?, 
                                price = ? 
                            WHERE product_id = ?";

    $stmt = $conn->prepare($update_product_sql);
    $stmt->bind_param("sddi", $product_name, $quantity_in_stock, $price, $product_id);

    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['success'] = false;
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
