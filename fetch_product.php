<?php
include 'db_connection.php';

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["product_id"])) {
    $product_id = $_GET["product_id"];

    $get_product_sql = "SELECT product_name, quantity_in_stock, price FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($get_product_sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['data'] = $result->fetch_assoc();
    } else {
        $response['success'] = false;
    }

    $stmt->close();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
