<?php
require("../includes/config.php");
session_start();

if (isset($_SESSION['retailer_login'])) {
    if ($_SESSION['retailer_login'] == true && isset($_SESSION['retailer_id'])) {
        $retailer_id = $_SESSION['retailer_id'];
        $error = "";
        $success_message = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['confirmed_order_id'])) {
                $confirmed_order_id = $_POST['confirmed_order_id'];

                // Fetch order items for the given order
                $query_orderItems = "SELECT pro_id, quantity, quantity_received FROM order_items WHERE order_id = '$confirmed_order_id'";
                $result_orderItems = mysqli_query($con, $query_orderItems);

                $orderComplete = true;
                $missingItems = [];
                while ($row = mysqli_fetch_assoc($result_orderItems)) {
                    if ($row['quantity'] != $row['quantity_received']) {
                        $orderComplete = false;
                        $missingItems[] = [
                            'pro_id' => $row['pro_id'],
                            'quantity' => $row['quantity'],
                            'quantity_received' => $row['quantity_received']
                        ];
                    }
                }
                 
                if ($orderComplete) {
                    // Update order status to 'received' and mark as complete
                    $query_confirmOrder = "UPDATE orders SET received = 1, received_date = NOW(), status = 1 WHERE order_id = '$confirmed_order_id' AND retailer_id = '$retailer_id'";
                    if (mysqli_query($con, $query_confirmOrder)) {
                        // Send notification to the manufacturer
                        $manufacturer_id = 1; // Manufacturer ID
                        $notification_message = "Order (ID: $confirmed_order_id) has been confirmed and received completely by the Scoop.";
                        $query_insertNotification = "INSERT INTO notifications (recipient_id, message, created_at) VALUES ('$manufacturer_id', '$notification_message', NOW())";
                        mysqli_query($con, $query_insertNotification);

                        $success_message = "Order confirmed as received and complete.";
                    } else {
                        $error = "Error confirming order.";
                    }
                } else {
                   
                    // Create a message for missing items
                   /* $missingMessage = "Order (ID: $confirmed_order_id) received with discrepancies: ";
                    foreach ($missingItems as $item) {
                        print_r ($missingItems);
                        die ;
                    */
                        // Fetch product name for the missing item
                        $query_productName = "SELECT pro_name FROM products WHERE pro_id = '{$item['pro_id']}'";
                        $result_productName = mysqli_query($con, $query_productName);
                        $product_name = "";
                        if ($row_productName = mysqli_fetch_assoc($result_productName)) {
                            $product_name = $row_productName['pro_name'];
                        }
                        $missingMessage .= "$product_name (Ordered: " . $item['quantity'] . ", Received: " . $item['quantity_received'] . "), ";
                    }
                    // Trim trailing comma and space
                    $missingMessage = rtrim($missingMessage, ', ');

                    // Send notification about missing items to the manufacturer
                    $manufacturer_id = 1; // Manufacturer ID
                    $query_insertNotification = "INSERT INTO notifications (recipient_id, message, created_at) VALUES ('$manufacturer_id', '$missingMessage', NOW())";
                    mysqli_query($con, $query_insertNotification);

                    //$_SESSION['discrepancy_message'] = $missingMessage;
                }
            } else {
                $error = "Invalid request.";
            }
        }
    } else {
        header('Location:../index.php');
    }
} else {
    header('Location:../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Confirm Order</title>
    <link rel="stylesheet" href="../includes/main_style.css">
    <script type="text/javascript" src="../includes/jquery.js"></script>
    <script> 
      let confirmOrderButton = $('.submit_button') 
      confirmOrderButton.click(()=>{

      })
     

    </script>
</head>
<body>
    <?php
        include("../includes/header.inc.php");
        include("../includes/nav_retailer.inc.php");
        include("../includes/aside_retailer.inc.php");
    ?>
    <section>
        <h1>Confirm Order</h1>
        <?php if (!empty($success_message)) { echo "<p class='success_message'>$success_message</p>"; } ?>
        <?php if (!empty($error)) { echo "<p class='error_message'>$error</p>"; } ?>
        <form id="confirmForm" action="confirm_order_scoup.php" method="POST" class="form">
            <input type="hidden" name="confirmed_order_id" value="<?php echo isset($_GET['order_id']) ? $_GET['order_id'] : ''; ?>" />
            <p>Are you sure you want to confirm this order as received and complete?</p>
            <button type="submit" class="submit_button" onclick="disableButton()">Confirm</button>
            <a href="view_orders.php" class="submit_button">Cancel</a>
        </form>
    </section>
    <?php
