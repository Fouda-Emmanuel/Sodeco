<?php
    require("../includes/config.php");
    include("../includes/validate_data.php");
    error_reporting(0);
    session_start();
    
    if(isset($_SESSION['admin_login'])) {
        $error = "";
        $querySelectRetailer = "SELECT *, area.area_id AS area_id FROM retailer, area WHERE retailer.area_id = area.area_id";
        $resultSelectRetailer = mysqli_query($con, $querySelectRetailer);
        
        if($_SERVER['REQUEST_METHOD'] == "POST") {
            if(isset($_POST['cmbFilter'])) {
                // Your existing code for searching orders
            }
        } else {
            $query_selectOrder = "SELECT * FROM orders, retailer, area WHERE orders.retailer_id = retailer.retailer_id AND retailer.area_id = area.area_id ORDER BY approved, status, order_id DESC;";
            $result_selectOrder = mysqli_query($con, $query_selectOrder);
        }
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
        include("../includes/nav_admin.inc.php");
        include("../includes/aside_admin.inc.php");
    ?>
    <section>
        <h1>Orders</h1>
        <form action="" method="POST" class="form">
            <!-- Search form -->
        </form>
        <table class="table_displayData" style="margin-top:20px;">
            <tr>
                <th>Order ID</th>
                <th>Scoup</th>
                <th>Date</th>
                <th>Approved Status</th>
                <th>Order Status</th>
                <th>Details</th>
            </tr>
            <?php while($row_selectOrder = mysqli_fetch_array($result_selectOrder)) { ?>
                <tr>
                    <td><?php echo $row_selectOrder['order_id']; ?></td>
                    <td><?php echo $row_selectOrder['area_code']; ?></td>
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
                </tr>
            <?php } ?>
        </table>
    </section>
    <?php
        include("../includes/footer.inc.php");
    ?>
</body>
</html>
