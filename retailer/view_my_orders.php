<?php
require("../includes/config.php");
include("../includes/validate_data.php");
session_start();

if (isset($_SESSION['retailer_login'])) {
    if ($_SESSION['retailer_login'] == true && isset($_SESSION['retailer_id'])) {
        $retailer_id = $_SESSION['retailer_id'];
        $error = "";
        $success_message = "";

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (isset($_POST['confirmed_order_id'])) {
                $confirmed_order_id = $_POST['confirmed_order_id'];

                // Update order status to 'received'
                $query_confirmOrder = "UPDATE orders SET received = 1, received_date = NOW() WHERE order_id = '$confirmed_order_id' AND retailer_id = '$retailer_id'";
                if (mysqli_query($con, $query_confirmOrder)) {
                    // Send notification to the manufacturer
                    $query_getManufacturerId = "SELECT man_id FROM orders WHERE order_id = '$confirmed_order_id'";
                    $result_getManufacturerId = mysqli_query($con, $query_getManufacturerId);
                    if ($result_getManufacturerId && mysqli_num_rows($result_getManufacturerId) > 0) {
                        $row_getManufacturerId = mysqli_fetch_assoc($result_getManufacturerId);
                        $manufacturer_id = $row_getManufacturerId['man_id'];

                        $notification_message = "Your order (ID: $confirmed_order_id) has been confirmed and received by the retailer.";
                        $query_insertNotification = "INSERT INTO notifications (recipient_id, message, created_at) VALUES ('$manufacturer_id', '$notification_message', NOW())";
                        mysqli_query($con, $query_insertNotification);

                        $success_message = "Order confirmed as received. Notification sent to manufacturer.";
                    } else {
                        $error = "Manufacturer ID not found for the order.";
                    }
                } else {
                    $error = "Error confirming order.";
                }
            }
        }

        // Fetch orders for the retailer
        $query_selectOrder = "SELECT * FROM orders WHERE retailer_id='$retailer_id'";
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
                if (confirm("Are you sure you want to confirm this order?")) {
                    var form = $('<form action="" method="POST">' +
                        '<input type="hidden" name="confirmed_order_id" value="' + orderID + '">' +
                        '</form>');
                    $('body').append(form);
                    form.submit();
                }
            });
        });
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
            <input type="submit" class="submit_button" value="Search" /> <span class="error_message"> <?php echo $error; ?> </span>
        </form>
        
        <form action="" method="POST" class="form" id="confirmForm">
            <table class="table_displayData" style="margin-top:20px;">
                <tr>
                    <th> Order ID </th>
                    <th> Date </th>
                    <th> Approved </th>
                    <th> Status </th>
                    <th> Details </th>
                    <th> Confirm </th>
                </tr>
                <?php $i = 1; while ($row_selectOrder = mysqli_fetch_array($result_selectOrder)) { ?>
                <tr>
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
                            } else {
                                echo "Completed";
                            }
                        ?>
                    </td>
                    <td> <a href="view_order_items.php?id=<?php echo $row_selectOrder['order_id']; ?>">Details</a> </td>
                    <td>
                        <?php if ($row_selectOrder['received'] == 0) { ?>
                            <input type="hidden" name="order_id" value="<?php echo $row_selectOrder['order_id']; ?>">
                            <button type="button" class="confirmButton">Confirm</button>
                        <?php } else { ?>
                            <span>Confirmed</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php $i++; } ?>
            </table>
        </form>
    </section>
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
