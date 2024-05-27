<?php
require("../includes/config.php");
include("../includes/validate_data.php");
session_start();

if (isset($_SESSION['manufacturer_login'])) {
    if ($_SESSION['manufacturer_login'] == true && isset($_SESSION['manufacturer_id'])) {
        $manufacturer_id = $_SESSION['manufacturer_id'];
        $query_selectNotifications = "SELECT * FROM notifications WHERE recipient_id = '$manufacturer_id' ORDER BY created_at DESC";
        $result_selectNotifications = mysqli_query($con, $query_selectNotifications);
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Notifications</title>
    <link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
    <?php
        include("../includes/header.inc.php");
        include("../includes/nav_manufacturer.inc.php");
        include("../includes/aside_manufacturer.inc.php");
    ?>
    <section>
        <h1>Notifications</h1>
        <table class="table_displayData">
            <tr>
                <th>Message</th>
                <th>Date</th>
            </tr>
            <?php while ($row_selectNotifications = mysqli_fetch_array($result_selectNotifications)) { ?>
            <tr>
                <td><?php echo $row_selectNotifications['message']; ?></td>
                <td><?php echo date("d-m-Y H:i:s", strtotime($row_selectNotifications['created_at'])); ?></td>
            </tr>
            <?php } ?>
        </table>
    </section>
    <?php include("../includes/footer.inc.php"); ?>
</body>
</html>
