<?php
require("../includes/config.php");
include("../includes/validate_data.php");
session_start();

if (isset($_SESSION['manufacturer_login'])) {
    if ($_SESSION['manufacturer_login'] == true && isset($_SESSION['manufacturer_id'])) {
        $manufacturer_id = $_SESSION['manufacturer_id'];

        // Handle delete notification request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_notification_id'])) {
            $notification_id = $_POST['delete_notification_id'];
            // Ensure the correct column name is used here
            $query_deleteNotification = "DELETE FROM notifications WHERE notification_id = '$notification_id' AND recipient_id = '$manufacturer_id'";
            if (mysqli_query($con, $query_deleteNotification)) {
                $success_message = "Notification deleted successfully.";
            } else {
                $error_message = "Error deleting notification.";
            }
        }

        // Fetch notifications for the manufacturer
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
    <style>
        .delete_button {
            background-color: #ff4d4d;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .delete_button:hover {
            background-color: #e60000;
        }
    </style>
</head>
<body>
    <?php
        include("../includes/header.inc.php");
        include("../includes/nav_manufacturer.inc.php");
        include("../includes/aside_manufacturer.inc.php");
    ?>
    <section>
        <h1>Notifications</h1>
        <?php if (!empty($success_message)) { echo "<p class='success_message'>$success_message</p>"; } ?>
        <?php if (!empty($error_message)) { echo "<p class='error_message'>$error_message</p>"; } ?>
        <table class="table_displayData">
            <tr>
                <th>Message</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php while ($row_selectNotifications = mysqli_fetch_array($result_selectNotifications)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row_selectNotifications['message']); ?></td>
                <td><?php echo date("d-m-Y H:i:s", strtotime($row_selectNotifications['created_at'])); ?></td>
                <td>
                    <form action="" method="POST" style="display:inline;">
                        <input type="hidden" name="delete_notification_id" value="<?php echo $row_selectNotifications['notification_id']; ?>">
                        <button type="submit" class="delete_button">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </section>
    <?php include("../includes/footer.inc.php"); ?>
</body>
</html>
