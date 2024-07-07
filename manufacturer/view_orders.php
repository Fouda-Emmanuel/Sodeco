<?php
require("../includes/config.php");
include("../includes/validate_data.php");
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);

session_start();

if(isset($_SESSION['manufacturer_login'])) {
    $error = "";
    
    // Check for notifications from scoup confirmation
    $notification_message = "";
    if (isset($_SESSION['head_of_service_notification'])) {
        $notification_message = $_SESSION['head_of_service_notification'];
        unset($_SESSION['head_of_service_notification']); // Clear the notification after displaying
        
        // Update notification message for the head of service (manufacturer)
        $manufacturer_id = $_SESSION['manufacturer_id']; // Assuming you have this stored in the session
        $queryUpdateNotification = "UPDATE manufacturer SET notification_message = '$notification_message' WHERE manufacturer_id = '$manufacturer_id'";
        mysqli_query($con, $queryUpdateNotification);
    }

    $querySelectRetailer = "SELECT *, area.area_id AS area_id FROM retailer, area WHERE retailer.area_id = area.area_id";
    $resultSelectRetailer = mysqli_query($con, $querySelectRetailer);

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        if(isset($_POST['chkId'])) {
            $chkId = $_POST['chkId'];
            foreach($chkId as $id) {
                // Update the order status to soft delete
                $query_softDeleteOrder = "UPDATE orders SET deleted = 1 WHERE order_id='$id'";
                $result_softDeleteOrder = mysqli_query($con, $query_softDeleteOrder);
                
                if(!$result_softDeleteOrder) {
                    echo "<script> alert(\"Failed to delete order with ID: $id\"); </script>";
                }
            }
            // Refresh the page after soft deletion
            header('Refresh:0');
        } else {
            echo "<script> alert(\"Please select orders to delete.\"); </script>";
        }
    }
    
    // Fetch orders
    $query_selectOrder = "SELECT * FROM orders, retailer, area WHERE orders.retailer_id = retailer.retailer_id AND retailer.area_id = area.area_id AND orders.deleted = 0 ORDER BY approved, status, order_id DESC";
    $result_selectOrder = mysqli_query($con, $query_selectOrder);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>
    <link rel="stylesheet" href="../includes/main_style.css">
    <link rel="stylesheet" href="css/smoothness/jquery-ui.css">
    <script type="text/javascript" src="../includes/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script>
    $(function() {
        $( "#datepicker" ).datepicker({
            changeMonth:true,
            changeYear:true,
            yearRange:"-100:+0",
            dateFormat:"yy-mm-dd"
        });
    });
    </script>
</head>
<body>
    <?php
    include("../includes/header.inc.php");
    include("../includes/nav_manufacturer.inc.php");
    include("../includes/aside_manufacturer.inc.php");
    ?>
    <section>
        <h1>Orders</h1>
        <?php if(!empty($notification_message)) echo "<p class='notification_message'>$notification_message</p>"; ?>
        <form action="" method="POST" class="form">
            <button type="submit" class="submit_button" name="delete_orders">Delete</button>
            <table class="table_displayData" style="margin-top:20px;">
                <tr>
                    <th><input type="checkbox" onClick="toggle(this)" /></th>
                    <th>Order ID</th>
                    <th>ScoupName</th>
                    <th>Area</th>
                    
                    <th>Date</th>
                    <th>Approved Status</th>
                    <th>Order Status</th>
                    <th>Details</th>
                    <th>Confirm</th>
                    <th>Generate Invoice</th>
                </tr>
                <?php while($row_selectOrder = mysqli_fetch_array($result_selectOrder)) { ?>
                <tr>
                    <td><input type="checkbox" name="chkId[]" value="<?php echo $row_selectOrder['order_id']; ?>" /></td>
                    <td><?php echo $row_selectOrder['order_id']; ?></td>
                    <td><?php echo $row_selectOrder['username']; ?></td>
                    <td><?php echo $row_selectOrder['area_name']; ?></td>
                    
                    <td><?php echo date("d-m-Y", strtotime($row_selectOrder['date'])); ?></td>
                    <td>
                        <?php
                            if($row_selectOrder['approved'] == 0) {
                                echo "Not Approved";
                            } else {
                                echo "Approved";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if($row_selectOrder['status'] == 0) {
                                echo "Pending";
                            } else {
                                echo "Completed";
                            }
                        ?>
                    </td>
                    <td><a href="view_order_items.php?id=<?php echo $row_selectOrder['order_id']; ?>">Details</a></td>
                    <td>
                        <?php
                            if($row_selectOrder['approved'] == 0) {
                                echo "<a href=\"confirm_order.php?id=".$row_selectOrder['order_id']."\">Confirm</a>";
                            }
                        ?>
                    </td>
                    <td>
                        <?php
                            if($row_selectOrder['approved'] == 1 && $row_selectOrder['status'] == 0) {
                            ?>
                                <a href="generate_invoice.php?id=<?php echo $row_selectOrder['order_id']; ?>">+ Invoice</a>
                            <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </form>
    </section>
    <?php
    include("../includes/footer.inc.php");
    ?>
    <script type="text/javascript">
        function toggle(source) {
            checkboxes = document.getElementsByName('chkId[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</body>
</html>
<?php
} else {
    header('Location:../index.php');
}
?>
