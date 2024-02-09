<?php
include 'db_connection.php';

// Function to calculate overall total
function calculateOverallTotal($result) {
    $overallTotal = 0;
    while($row = $result->fetch_assoc()) {
        $totalPrice = $row["quantity_sold"] * $row["price"];
        $overallTotal += $totalPrice;
    }
    return $overallTotal;
}

// Function to reset sales table
function resetSalesTable() {
    global $conn;
    $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : null;

    if ($confirm === 'yes') {
        $reset_sql = "DELETE FROM sales";
        if ($conn->query($reset_sql) === TRUE) {
            echo "Sales table has been reset.";
        } else {
            echo "Error resetting sales table: " . $conn->error;
        }
    }
}

resetSalesTable();

$sql = "SELECT sales.sale_id, products.product_name, sales.quantity_sold, sales.sale_date, products.price
        FROM sales
        INNER JOIN products ON sales.product_id = products.product_id";
$result = $conn->query($sql);
$overallTotal = calculateOverallTotal($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Sales</title>
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
    </style>
</head>
<body>
    <h2>View Sales</h2>
    <form id="resetForm" method="post" action="">
    <button type="button" onclick="confirmReset()">Reset Sales Table</button>
    <input type="hidden" name="confirm" id="confirmInput" value="">
</form>

<script>
function confirmReset() {
    var confirmed = confirm('Are you sure you want to reset the sales table? This action cannot be undone.');
    if (confirmed) {
        document.getElementById('confirmInput').value = 'yes';
        document.getElementById('resetForm').submit();
    }
}
</script>

    <table>
        <tr>
            <th>Sale ID</th>
            <th>Product Name</th>
            <th>Quantity Sold</th>
            <th>Price per Unit</th>
            <th>Total Price</th>
            <th>Sale Date</th>
        </tr>
        <?php
        // Fetch and display sales data
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $totalPrice = $row["quantity_sold"] * $row["price"];
            echo "<tr>
                    <td>".$row["sale_id"]."</td>
                    <td>".$row["product_name"]."</td>
                    <td>".$row["quantity_sold"]."</td>
                    <td>".$row["price"]."</td>
                    <td>".$totalPrice."</td>
                    <td>".$row["sale_date"]."</td>
                </tr>";
        }
        ?>

        <!-- Display overall total row -->
        <tr class="total-row">
            <td colspan="4">Overall Total</td>
            <td><?php echo $overallTotal; ?></td>
            <td></td>
        </tr>
    </table>
</body>
</html>
