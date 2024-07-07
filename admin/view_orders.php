<?php
require("../includes/config.php");
include("../includes/validate_data.php");
error_reporting(E_ALL); // Enable error reporting
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['admin_login'])) {
    $error = "";
    $querySelectRetailer = "SELECT *, area.area_id AS area_id FROM retailer, area WHERE retailer.area_id = area.area_id";
    $resultSelectRetailer = mysqli_query($con, $querySelectRetailer);

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (isset($_POST['cmbFilter'])) {
            // Your existing code for searching orders
        } elseif (isset($_POST['chkId'])) {
            $chkId = $_POST['chkId'];
            foreach ($chkId as $id) {
                // Mark the order as deleted
                $query_markDeleted = "UPDATE orders SET deleted = 1 WHERE order_id = '$id'";
                mysqli_query($con, $query_markDeleted);
            }
            header('Refresh:0'); // Refresh the page after deletion
        } else {
            echo "<script> alert(\"Please select orders to delete.\"); </script>";
        }
    }

    // Fetch orders that are not marked as deleted
    $query_selectOrder = "SELECT * FROM orders, retailer, area WHERE orders.retailer_id = retailer.retailer_id AND retailer.area_id = area.area_id AND orders.deleted = 0 ORDER BY approved, status, order_id DESC";
    $result_selectOrder = mysqli_query($con, $query_selectOrder);
} else {
    header('Location:../index.php');
}
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
            changeMonth: true,
            changeYear: true,
            yearRange: "-100:+0",
            dateFormat: "yy-mm-dd"
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
    include("../includes/nav_admin.inc.php");
    include("../includes/aside_admin.inc.php");
    ?>
    <section>
        <h1>Orders</h1>
        <form action="" method="POST" class="form">
            <button type="submit" class="submit_button" name="delete_orders">Delete</button>
            <table class="table_displayData" style="margin-top:20px;">
                <tr>
                    <th><input type="checkbox" onClick="toggle(this)" /></th>
                    <th>Order ID</th>
                    <th>Scoup Name</th>
                    <th>Area</th>
                    <th>Date</th>
                    <th>Approved Status</th>
                    <th>Order Status</th>
                    <th>Details</th>
                </tr>
                <?php while ($row_selectOrder = mysqli_fetch_array($result_selectOrder)) { ?>
                <tr>
                    <td><input type="checkbox" name="chkId[]" value="<?php echo $row_selectOrder['order_id']; ?>" /></td>
                    <td><?php echo $row_selectOrder['order_id']; ?></td>
                    <td><?php echo $row_selectOrder['username']; ?></td>
                    <td><?php echo $row_selectOrder['area_code']; ?></td>
                    <td><?php echo date("d-m-Y", strtotime($row_selectOrder['date'])); ?></td>
                    <td>
                        <?php echo ($row_selectOrder['approved'] == 0) ? "Not Approved" : "Approved"; ?>
                    </td>
                    <td>
                        <?php echo ($row_selectOrder['status'] == 0) ? "Pending" : "Completed"; ?>
                    </td>
                    <td><a href="view_order_items.php?id=<?php echo $row_selectOrder['order_id']; ?>">Details</a></td>
                </tr>
                <?php } ?>
            </table>
        </form>
    </section>
    <?php
    include("../includes/footer.inc.php");
    ?>
</body>
</html>
<?php
?>
