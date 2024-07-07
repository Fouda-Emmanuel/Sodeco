<?php
include("../includes/config.php");
session_start();
if (isset($_SESSION['admin_login']) && $_SESSION['admin_login'] == true) {
    $query_selectRetailer = "SELECT * FROM retailer, area WHERE retailer.area_id = area.area_id";
    $result_selectRetailer = mysqli_query($con, $query_selectRetailer);

    if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['chkId'])) {
        $chkId = $_POST['chkId'];
        foreach ($chkId as $id) {
            // First, get all orders related to the retailer
            $query_getOrders = "SELECT order_id FROM orders WHERE retailer_id = '$id'";
            $result_getOrders = mysqli_query($con, $query_getOrders);

            while ($row_order = mysqli_fetch_array($result_getOrders)) {
                $order_id = $row_order['order_id'];

                // Get all invoices related to each order
                $query_getInvoices = "SELECT invoice_id FROM invoice WHERE order_id = '$order_id'";
                $result_getInvoices = mysqli_query($con, $query_getInvoices);

                while ($row_invoice = mysqli_fetch_array($result_getInvoices)) {
                    $invoice_id = $row_invoice['invoice_id'];

                    // Delete invoice items related to each invoice
                    $query_deleteInvoiceItems = "DELETE FROM invoice_items WHERE invoice_id = '$invoice_id'";
                    $result_invoiceItems = mysqli_query($con, $query_deleteInvoiceItems);

                    if (!$result_invoiceItems) {
                        echo "<script> alert(\"There was some problem deleting the invoice items\"); </script>";
                        header('Refresh:0');
                        exit;
                    }
                }

                // Delete invoices related to each order
                $query_deleteInvoices = "DELETE FROM invoice WHERE order_id = '$order_id'";
                $result_invoices = mysqli_query($con, $query_deleteInvoices);

                if (!$result_invoices) {
                    echo "<script> alert(\"There was some problem deleting the invoices\"); </script>";
                    header('Refresh:0');
                    exit;
                }

                // Delete order items related to each order
                $query_deleteOrderItems = "DELETE FROM order_items WHERE order_id = '$order_id'";
                $result_orderItems = mysqli_query($con, $query_deleteOrderItems);

                if (!$result_orderItems) {
                    echo "<script> alert(\"There was some problem deleting the order items\"); </script>";
                    header('Refresh:0');
                    exit;
                }
            }

            // Delete the orders related to the retailer
            $query_deleteOrders = "DELETE FROM orders WHERE retailer_id = '$id'";
            $result_orders = mysqli_query($con, $query_deleteOrders);

            if (!$result_orders) {
                echo "<script> alert(\"There was some problem deleting the orders\"); </script>";
                header('Refresh:0');
                exit;
            }

            // Finally, delete the retailer
            $query_deleteRetailer = "DELETE FROM retailer WHERE retailer_id = '$id'";
            $result_retailer = mysqli_query($con, $query_deleteRetailer);

            if (!$result_retailer) {
                echo "<script> alert(\"There was some problem deleting the retailer\"); </script>";
                header('Refresh:0');
                exit;
            }
        }

        echo "<script> alert(\"Scoup Deleted Successfully\"); </script>";
        header('Refresh:0');
    }
} else {
    header('Location:../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Scoup</title>
    <link rel="stylesheet" href="../includes/main_style.css">
    <script language="JavaScript">
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
        <h1>View Scoup</h1>
        <form action="" method="POST" class="form">
            <table class="table_displayData">
                <tr>
                    <th><input type="checkbox" onClick="toggle(this)" /></th>
                    <th>Sr. No.</th>
                    <th>Username</th>
                    <th>Area Code</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Edit</th>
                </tr>
                <?php $i = 1; while ($row_selectRetailer = mysqli_fetch_array($result_selectRetailer)) { ?>
                <tr>
                    <td><input type="checkbox" name="chkId[]" value="<?php echo $row_selectRetailer['retailer_id']; ?>" /></td>
                    <td><?php echo $i; ?></td>
                    <td><?php echo $row_selectRetailer['username']; ?></td>
                    <td><?php echo $row_selectRetailer['area_code']; ?></td>
                    <td><?php echo $row_selectRetailer['phone']; ?></td>
                    <td><?php echo $row_selectRetailer['email']; ?></td>
                    <td><?php echo $row_selectRetailer['address']; ?></td>
                    <td><a href="edit_retailer.php?id=<?php echo $row_selectRetailer['retailer_id']; ?>"><img src="../images/edit.png" alt="edit" /></a></td>
                </tr>
                <?php $i++; } ?>
            </table>
            <input type="submit" value="Delete" class="submit_button" />
        </form>
    </section>
    <?php
    include("../includes/footer.inc.php");
    ?>
</body>
</html>
