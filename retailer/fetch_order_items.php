<?php
require("../includes/config.php");
session_start();

if (isset($_SESSION['retailer_id']) && isset($_POST['order_id'])) {
    $retailer_id = $_SESSION['retailer_id']; // Fetch retailer ID from session
    $order_id = $_POST['order_id'];
    $orderComplete = true; // Flag to track order completeness
    $discrepancies = array(); // Array to store product discrepancies

    // Fetch order items and product names
    $query_orderItems = "
        SELECT 
            oi.pro_id, 
            p.pro_name AS product_name, 
            oi.quantity AS quantity_ordered, 
            oi.quantity_received 
        FROM 
            order_items oi 
        JOIN 
            products p 
        ON 
            oi.pro_id = p.pro_id 
        WHERE 
            oi.order_id = '$order_id'";
    
    $result_orderItems = mysqli_query($con, $query_orderItems);

    if (mysqli_num_rows($result_orderItems) > 0) {
        while ($row_orderItems = mysqli_fetch_assoc($result_orderItems)) {
            // Check if received quantity matches ordered quantity
            if ($row_orderItems['quantity_ordered'] != $row_orderItems['quantity_received']) {
                $orderComplete = false;
                // Store discrepancies
                $discrepancies[] = "{$row_orderItems['product_name']} (Ordered: {$row_orderItems['quantity_ordered']}, Received: {$row_orderItems['quantity_received']})";
            
            }
            
            // Display input fields for ordered and received quantities
            echo "<div>
                    <label>{$row_orderItems['product_name']}</label>
                    <input type='number' name='quantity_ordered[{$row_orderItems['product_name']}]' value='{$row_orderItems['quantity_ordered']}' readonly>
                    <input type='number' name='quantity_received[{$row_orderItems['product_name']}]' value='{$row_orderItems['quantity_received']}' required>
                </div>";
        }
         

    } else {
        echo "<p>No items found for this order.</p>";
    }
} else {
    echo "<p>Error: Retailer ID or Order ID is missing.</p>";
}
?>
