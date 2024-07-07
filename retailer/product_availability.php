<?php
include("../includes/config.php");
session_start();

if (isset($_SESSION['retailer_login']) && $_SESSION['retailer_login'] == true) {
    $retailer_id = $_SESSION['retailer_id'];

    // Get retailer's sector
    $query_getRetailerSector = "SELECT sector_id FROM retailer WHERE retailer_id = $retailer_id";
    $result_getRetailerSector = mysqli_query($con, $query_getRetailerSector);
    $row_retailer = mysqli_fetch_assoc($result_getRetailerSector);
    $sector_id = $row_retailer['sector_id'];

    // Get products available in the retailer's sector
    $query_getProducts = "SELECT * FROM products WHERE sector_id = $sector_id";
    $result_getProducts = mysqli_query($con, $query_getProducts);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Availability</title>
    <link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
    <?php
    include("../includes/header.inc.php");
    include("../includes/nav_retailer.inc.php");
    include("../includes/aside_retailer.inc.php");
    ?>
    <section>
        <h1>Available Products</h1>
        <table>
            <tr>
                <th>Product Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Unit</th>
                <th>Category</th>
                <th>Zone</th>
                <th>Quantity</th>
                <th>Action</th>
            </tr>
            <?php while($row_product = mysqli_fetch_assoc($result_getProducts)) { ?>
            <tr>
                <td><?php echo $row_product['pro_name']; ?></td>
                <td><?php echo $row_product['pro_desc']; ?></td>
                <td><?php echo $row_product['pro_price']; ?></td>
                <td><?php echo $row_product['unit']; ?></td>
                <td><?php echo $row_product['pro_cat']; ?></td>
                <td><?php echo $row_product['zone_id']; ?></td>
                <td><?php echo $row_product['quantity']; ?></td>
                <td><a href="add_order.php?product_id=<?php echo $row_product['pro_id']; ?>">Order</a></td>
            </tr>
            <?php } ?>
        </table>
    </section>
</body>
</html>
<?php
} else {
    header('Location:../index.php');
}
?>
