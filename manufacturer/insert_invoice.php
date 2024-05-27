<?php
require("../includes/config.php");
session_start();

$currentDate = date('Y-m-d');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];

    if (!isset($_POST['distributor'])) {
        $_SESSION['error'] = "* Please choose distributor";
        header("Location: generate_invoice.php?id=$order_id");
        exit; // Terminate script execution after redirect
    }

    $dist_id = $_POST['distributor'];
    $comment = isset($_POST['txtComment']) ? $_POST['txtComment'] : '';

    // Retrieve order information
    $query_selectOrder = "SELECT retailer_id, total_amount FROM orders WHERE order_id='$order_id'";
    $result_selectOrder = mysqli_query($con, $query_selectOrder);

    if (!$result_selectOrder) {
        echo "Error: " . mysqli_error($con);
        exit;
    }

    $row_selectOrder = mysqli_fetch_array($result_selectOrder);
    
    // Check if order information is retrieved successfully
    if (!$row_selectOrder) {
        echo "Error: Order not found";
        exit;
    }

    $retailer_id = $row_selectOrder['retailer_id'];
    $total_amount = $row_selectOrder['total_amount'];

    // Retrieve auto-incremented invoice ID
    $query_selectInvoiceId = "SELECT AUTO_INCREMENT FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'sourcecodester_scm_new' AND TABLE_NAME = 'invoice'";
$result_selectInvoiceId = mysqli_query($con, $query_selectInvoiceId);

// Check if the query was successful
if (!$result_selectInvoiceId) {
    echo "Error: " . mysqli_error($con);
    exit; // Terminate script execution
}

// Fetch the row containing the AUTO_INCREMENT value
$row_selectInvoiceId = mysqli_fetch_assoc($result_selectInvoiceId);

// Check if the fetched row contains the AUTO_INCREMENT value
if(isset($row_selectInvoiceId['AUTO_INCREMENT'])) {
    // Assign the AUTO_INCREMENT value to the $invoice_id variable
    $invoice_id = $row_selectInvoiceId['AUTO_INCREMENT'];
} else {
    // Display an error message if the AUTO_INCREMENT value is not found
    echo "Error: Failed to retrieve AUTO_INCREMENT value";
    exit; // Terminate script execution
}

    // Insert invoice into database
    $queryInsertInvoice = "INSERT INTO invoice(order_id, retailer_id, dist_id, date, total_amount, comments) VALUES('$order_id', '$retailer_id', '$dist_id', '$currentDate', '$total_amount', '$comment')";
    
    if (mysqli_query($con, $queryInsertInvoice)) {
        // Insert invoice items into database
        $query_selectOrderItems = "SELECT * FROM order_items WHERE order_id='$order_id'";
        $result_selectOrderItems = mysqli_query($con, $query_selectOrderItems);

        while ($row_selectOrderItems = mysqli_fetch_array($result_selectOrderItems)) {
            $product_id = $row_selectOrderItems['pro_id'];
            $quantity = $row_selectOrderItems['quantity'];

            $queryInsertInvoiceItems = "INSERT INTO invoice_items(invoice_id, product_id, quantity) VALUES('$invoice_id', '$product_id', '$quantity')";
            if (!mysqli_query($con, $queryInsertInvoiceItems)) {
                echo "Error: Failed to insert invoice items";
                exit; // Terminate script execution
            }
        }

        // Update order status
        $queryUpdateStatus = "UPDATE orders SET status=1 WHERE order_id='$order_id'";
        if (mysqli_query($con, $queryUpdateStatus)) {
            echo "<script> alert(\"Invoice Generated Successfully\"); </script>";
            header("Refresh:0;url=view_invoice_items.php?id=$invoice_id");
            exit; // Terminate script execution after redirect
        } else {
            echo "Error: Failed to update order status";
            exit; // Terminate script execution
        }
    } else {
        echo "Error: Failed to insert invoice";
        exit; // Terminate script execution
    }
}
?>
