<?php
require("../includes/config.php");
include("../includes/validate_data.php");
session_start();

if (isset($_SESSION['retailer_login'])) {
    if ($_SESSION['retailer_login'] == true && isset($_SESSION['retailer_id'])) {
        $retailer_id = $_SESSION['retailer_id'];
        $error = "";
        $success_message = "";

        // Fetch retailer's username
        $query_retailerName = "SELECT username FROM retailer WHERE retailer_id = '$retailer_id'";
        $result_retailerName = mysqli_query($con, $query_retailerName);

        // Check if the query was successful
        if ($result_retailerName && mysqli_num_rows($result_retailerName) > 0) {
            $retailer_name = mysqli_fetch_assoc($result_retailerName)['username'];
        } else {
            $error = "Error fetching retailer name.";
            $retailer_name = "Unknown Retailer"; // Fallback in case of error
        }

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['confirm_order'])) {
                $order_id = $_POST['order_id'];
                $quantities_ordered = $_POST['quantity_ordered'];
                $quantities_received = $_POST['quantity_received'];

                $missing_items = [];
                $orderComplete = true;

                foreach ($quantities_ordered as $item_id => $quantity_ordered) {
                    $quantity_received = $quantities_received[$item_id];
                    if ($quantity_ordered != $quantity_received) {
                        $orderComplete = false;
                        $missing_items[] = " $item_id (Ordered: $quantity_ordered, Received: $quantity_received)";
                    }

                    // Update the order_items table with received quantities
                    $query_updateOrderItem = "UPDATE order_items SET quantity_received = '$quantity_received' WHERE order_id = '$order_id' AND pro_id = '$item_id'";
                    mysqli_query($con, $query_updateOrderItem);
                }

                if ($orderComplete) {
                    // Update order status to 'received' and mark as complete
                    $query_confirmOrder = "UPDATE orders SET received = 1, received_date = NOW(), status = 1 WHERE order_id = '$order_id' AND retailer_id = '$retailer_id'";
                    if (mysqli_query($con, $query_confirmOrder)) {
                        // Send notification to the manufacturer and the head of service
                        $manufacturer_id = 1; // Replace with the actual ID of the manufacturer
                        $notification_message = "Order (ID: $order_id) has been confirmed and received completely by the Scoop $retailer_name.";
                        $query_insertNotification = "INSERT INTO notifications (recipient_id, message, created_at) VALUES ('$manufacturer_id', '$notification_message', NOW())";
                        mysqli_query($con, $query_insertNotification);

                        $success_message = "Order confirmed as received and complete.";
                    } else {
                        $error = "Error confirming order.";
                    }
                } else {
                    $query_confirmOrder = "UPDATE orders SET received = 2, received_date = NOW(), status = 2 WHERE order_id = '$order_id' AND retailer_id = '$retailer_id'";
                    mysqli_query($con, $query_confirmOrder);
                    $error = "Order received with discrepancies: " . implode(", ", $missing_items);
                    // Send notification to the manufacturer about the discrepancies
                    $manufacturer_id = 1; // Replace with the actual ID of the manufacturer
                    $notification_message = "Order (ID: $order_id) received with discrepancies: " . implode(", ", $missing_items) . " by the Scoop $retailer_name";
                    $query_insertNotification = "INSERT INTO notifications (recipient_id, message, created_at) VALUES ('$manufacturer_id', '$notification_message', NOW())";
                    mysqli_query($con, $query_insertNotification);
                }
            }

            // Handle deletion of orders
            if (isset($_POST['delete_orders'])) {
                if (!empty($_POST['chkId'])) {
                    $delete_orders_ids = $_POST['chkId'];
                    foreach ($delete_orders_ids as $delete_order_id) {
                        $query_deleteOrder = "UPDATE orders SET deleted = 1 WHERE order_id = '$delete_order_id' AND retailer_id = '$retailer_id'";
                        mysqli_query($con, $query_deleteOrder);
                    }
                    $success_message = "Selected orders have been deleted.";
                } else {
                    $error = "No orders selected for deletion.";
                }
            }
        }

        // Fetch orders for the retailer that are not marked as deleted
        $query_selectOrder = "SELECT * FROM orders WHERE retailer_id='$retailer_id' AND deleted = 0";
        $result_selectOrder = mysqli_query($con, $query_selectOrder);
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
    <title>My Orders</title>
    <link rel="stylesheet" href="../includes/main_style.css">
    <link rel="stylesheet" href="css/smoothness/jquery-ui.css">
    <script type="text/javascript" src="../includes/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0",
                dateFormat: "yy-mm-dd"
            });

            $(".confirmButton").click(function() {
                var orderID = $(this).closest("tr").find("input[name='order_id']").val();
                $("#order_id").val(orderID);
                $("#confirmationModal").show();

                // Fetch order items and populate the modal
                $.post('fetch_order_items.php', {order_id: orderID}, function(data) {
                    $('#orderItemsContainer').html(data);
                });
            });

            $("#closeModal").click(function() {
                $("#confirmationModal").hide();
            });
        });

        function toggle(source) {
            checkboxes = document.getElementsByName('chkId[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</head>
<body>
    <?php
        include("../includes/header.inc.php");
        include("../includes/nav_retailer.inc.php");
        include("../includes/aside_retailer.inc.php");
    ?>
    <section>
        <h1>My Orders</h1>
        <?php if (!empty($success_message)) { echo "<p class='success_message'>$success_message</p>"; } ?>
        <?php if (!empty($error)) { echo "<p class='error_message'>$error</p>"; } ?>
        <form action="" method="POST" class="form">
            Search By: 
            <div class="input-box">
                <select name="cmbFilter" id="cmbFilter">
                    <option value="" disabled selected>-- Search By --</option>
                    <option value="id"> Id </option>
                    <option value="date"> Date </option>
                    <option value="status"> Status </option>
                    <option value="approved"> Approval </option>
                </select>
            </div>
            <div class="input-box"> <input type="text" name="txtId" id="txtId" style="display:none;" /> </div>
            <div class="input-box"> <input type="text" id="datepicker" name="txtDate" style="display:none;"/> </div>
            <div class="input-box">
                <select name="cmbStatus" id="cmbStatus" style="display:none;">
                    <option value="" disabled selected>-- Select Option --</option>
                    <option value="zero"> Pending </option>
                    <option value="1"> Completed </option>
                </select>
            </div>
            <div class="input-box">
                <select name="cmbApproved" id="cmbApproved" style="display:none;">
                    <option value="" disabled selected>-- Select Option --</option>
                    <option value="zero"> Not Approved </option>
                    <option value="1"> Approved </option>
                </select>
            </div>
            <input type="submit" class="submit_button" value="Search" />  
        </form>
        
        <form action="" method="POST" class="form">
            <button type="submit" class="submit_button" name="delete_orders">Delete</button>
            <table class="table_displayData" style="margin-top:20px;">
                <tr>
                    <th><input type="checkbox" onClick="toggle(this)" /></th>
                    <th> Order ID </th>
                    <th> Date </th>
                    <th> Approved </th>
                    <th> Status </th>
                    <th> Details </th>
                    <th> Confirm </th>
                </tr>
                <?php $i = 1; while ($row_selectOrder = mysqli_fetch_array($result_selectOrder)) { ?>
                <tr>
                    <td><input type="checkbox" name="chkId[]" value="<?php echo $row_selectOrder['order_id']; ?>" /></td>
                    <td> <?php echo $row_selectOrder['order_id']; ?> </td>
                    <td> <?php echo date("d-m-Y", strtotime($row_selectOrder['date'])); ?> </td>
                    <td>
                        <?php
                            if ($row_selectOrder['approved'] == 0) {
                                echo "Not Approved";
                            } else {
                                echo "Approved";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if ($row_selectOrder['status'] == 0) {
                                echo "Pending";
                            } else if ($row_selectOrder['status'] == 1){
                                echo "Completed";
                            }
                            else print 'Incomplete'
                        ?>
                    </td>
                    <td> <a href="view_order_items.php?id=<?php echo $row_selectOrder['order_id']; ?>">Details</a> </td>
                    <td>
                        <?php if ($row_selectOrder['received'] == 0) { ?>
                            <input type="hidden" name="order_id" value="<?php echo $row_selectOrder['order_id']; ?>">
                            <button type="button" class="confirmButton">Confirm</button>
                        <?php } else if($row_selectOrder['received'] == 1){ ?>
                            <span>Confirmed</span>
                        <?php } else { ?>
                            <span>Incomplete</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php $i++; } ?>
            </table>
        </form>
    </section>

    <!-- Modal for confirmation -->
    <div id="confirmationModal" style="display:none;">
        <form action="" method="POST">
            <input type="hidden" name="order_id" id="order_id">
            <h2>Confirm Order</h2>
            <p>Enter the quantities ordered and received for each product:</p>
            <div id="orderItemsContainer"></div>
            <button type="submit" name="confirm_order">Confirm</button>
            <button type="button" id="closeModal">Cancel</button>
        </form>
    </div>

    <?php
        include("../includes/footer.inc.php");
    ?>
    <script type="text/javascript">
        $('#cmbFilter').change(function() {
            var selected = $(this).val();
            if (selected == "id") {
                $('#txtId').show();
                $('#datepicker').hide();
                $('#cmbStatus').hide();
                $('#cmbApproved').hide();
            } else if (selected == "date") {
                $('#txtId').hide();
                $('#datepicker').show();
                $('#cmbStatus').hide();
                $('#cmbApproved').hide();
            } else if (selected == "status") {
                $('#txtId').hide();
                $('#datepicker').hide();
                $('#cmbStatus').show();
                $('#cmbApproved').hide();
            } else if (selected == "approved") {
                $('#txtId').hide();
                $('#datepicker').hide();
                $('#cmbStatus').hide();
                $('#cmbApproved').show();
            }
        });
    </script>
</body>
</html>
