<?php
include("../includes/config.php");
include("../includes/validate_data.php");
session_start();
if (isset($_SESSION['manufacturer_login'])) {
    if ($_SESSION['manufacturer_login'] == true) {
        $query_selectCategory = "SELECT cat_id, cat_name FROM categories";
        $query_selectUnit = "SELECT id, unit_name FROM unit";
        $query_selectZone = "SELECT zone_id, zone_name FROM zone";
        $query_selectSector = "SELECT sector_id, sector_name FROM sector";
        
        $result_selectCategory = mysqli_query($con, $query_selectCategory);
        $result_selectUnit = mysqli_query($con, $query_selectUnit);
        $result_selectZone = mysqli_query($con, $query_selectZone);
        $result_selectSector = mysqli_query($con, $query_selectSector);
        
        $name = $price = $unit = $category = $zone = $sector = $rdbStock = $description = "";
        $nameErr = $priceErr = $requireErr = $confirmMessage = "";
        $nameHolder = $priceHolder = $descriptionHolder = "";
        
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            if (!empty($_POST['txtProductName'])) {
                $nameHolder = $_POST['txtProductName'];
                $name = $_POST['txtProductName'];
            }
            if (!empty($_POST['txtProductPrice'])) {
                $priceHolder = $_POST['txtProductPrice'];
                $resultValidate_price = validate_price($_POST['txtProductPrice']);
                if ($resultValidate_price == 1) {
                    $price = $_POST['txtProductPrice'];
                } else {
                    $priceErr = $resultValidate_price;
                }
            }
            if (isset($_POST['cmbProductUnit'])) {
                $unit = $_POST['cmbProductUnit'];
            }
            if (isset($_POST['cmbProductCategory'])) {
                $category = $_POST['cmbProductCategory'];
            }
            if (isset($_POST['cmbProductZone'])) {
                $zone = $_POST['cmbProductZone'];
            }
            if (isset($_POST['cmbProductSector'])) {
                $sector = $_POST['cmbProductSector'];
            }
            if (empty($_POST['rdbStock'])) {
                $rdbStock = "";
            } else {
                if ($_POST['rdbStock'] == 1) {
                    $rdbStock = 1;
                } else if ($_POST['rdbStock'] == 2) {
                    $rdbStock = 2;
                }
            }
            if (!empty($_POST['txtProductDescription'])) {
                $description = $_POST['txtProductDescription'];
                $descriptionHolder = $_POST['txtProductDescription'];
            }
            
            if ($name != null && $price != null && $unit != null && $category != null && $zone != null && $sector != null && $rdbStock == 1) {
                $rdbStock = 0;
                $query_addProduct = "INSERT INTO products(pro_name, pro_desc, pro_price, unit, pro_cat, zone_id, sector_id, quantity) VALUES('$name', '$description', '$price', '$unit', '$category', '$zone', '$sector', '$rdbStock')";
                if (mysqli_query($con, $query_addProduct)) {
                    echo "<script> alert(\"Product Added Successfully\"); </script>";
                    header('Refresh:0');
                } else {
                    $requireErr = "Adding Product Failed";
                }
            } else if ($name != null && $price != null && $unit != null && $category != null && $zone != null && $sector != null && $rdbStock == 2) {
                $query_addProduct = "INSERT INTO products(pro_name, pro_desc, pro_price, unit, pro_cat, zone_id, sector_id, quantity) VALUES('$name', '$description', '$price', '$unit', '$category', '$zone', '$sector', NULL)";
                if (mysqli_query($con, $query_addProduct)) {
                    echo "<script> alert(\"Product Added Successfully\"); </script>";
                    header('Refresh:0');
                } else {
                    $requireErr = "Adding Product Failed";
                }
            } else {
                $requireErr = "* All Fields are Compulsory with valid values except Description";
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
    <title>Add Product</title>
    <link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
    <?php
    include("../includes/header.inc.php");
    include("../includes/nav_manufacturer.inc.php");
    include("../includes/aside_manufacturer.inc.php");
    ?>
    <section>
        <h1>Add Product</h1>
        <form action="" method="POST" class="form">
            <ul class="form-list">
                <li>
                    <div class="label-block">
                        <label for="product:name">Product Name</label>
                    </div>
                    <div class="input-box">
                        <input type="text" id="product:name" name="txtProductName" placeholder="Product Name" value="<?php echo $nameHolder; ?>" required />
                    </div>
                    <span class="error_message"><?php echo $nameErr; ?></span>
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:price">Price</label>
                    </div>
                    <div class="input-box">
                        <input type="text" id="product:price" name="txtProductPrice" placeholder="Price" value="<?php echo $priceHolder; ?>" required />
                    </div>
                    <span class="error_message"><?php echo $priceErr; ?></span>
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:unit">Unit Type</label>
                    </div>
                    <div class="input-box">
                        <select name="cmbProductUnit" id="product:unit">
                            <option value="" disabled selected>--- Select Unit ---</option>
                            <?php while ($row_selectUnit = mysqli_fetch_array($result_selectUnit)) { ?>
                                <option value="<?php echo $row_selectUnit["id"]; ?>"><?php echo $row_selectUnit["unit_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:category">Category</label>
                    </div>
                    <div class="input-box">
                        <select name="cmbProductCategory" id="product:category">
                            <option value="" disabled selected>--- Select Category ---</option>
                            <?php while ($row_selectCategory = mysqli_fetch_array($result_selectCategory)) { ?>
                                <option value="<?php echo $row_selectCategory["cat_id"]; ?>"><?php echo $row_selectCategory["cat_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </li>
                <li>
                   
                <li>
                    <div class="label-block">
                        <label for="product:zone">Zone</label>
                    </div>
                    <div class="input-box">
                        <select name="cmbProductZone" id="product:zone">
                            <option value="" disabled selected>--- Select Zone ---</option>
                            <?php while ($row_selectZone = mysqli_fetch_array($result_selectZone)) { ?>
                                <option value="<?php echo $row_selectZone["zone_id"]; ?>"><?php echo $row_selectZone["zone_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:sector">Sector</label>
                    </div>
                    <div class="input-box">
                        <select name="cmbProductSector" id="product:sector">
                            <option value="" disabled selected>--- Select Sector ---</option>
                            <?php while ($row_selectSector = mysqli_fetch_array($result_selectSector)) { ?>
                                <option value="<?php echo $row_selectSector["sector_id"]; ?>"><?php echo $row_selectSector["sector_name"]; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:stock">Stock Management</label>
                    </div>
                    <input type="radio" name="rdbStock" value="1"> Enable
                </li>
                <li>
                    <div class="label-block">
                        <label for="product:description">Description</label>
                    </div>
                    <div class="input-box">
                        <textarea type="text" id="product:description" name="txtProductDescription" placeholder="Description"><?php echo $descriptionHolder; ?></textarea>
                    </div>
                </li>
                <li>
                    <input type="submit" value="Add Product" class="submit_button" />
                    <span class="error_message"> <?php echo $requireErr; ?> </span>
                </li>
            </ul>
        </form>
    </section>
</body>
</html>
