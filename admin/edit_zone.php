<?php
	include("../includes/config.php");
	include("../includes/validate_data.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$zoneName = $zoneCode = "";
			$zoneNameErr = $zoneCodeErr = $requireErr = $confirmMessage = "";
			$zoneNameHolder = $zoneCodeHolder = "";
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = $_GET['id'];
				if(!empty($_POST['txtZoneName'])) {
					$result = validate_name($_POST['txtZoneName']);
					if($result == 1) {
						$zoneName = $_POST['txtZoneName'];
					}
					else{
						$zoneNameErr = $result;
					}
				}
				if(!empty($_POST['txtZoneCode'])) {
					$zoneCode = $_POST['txtZoneCode'];
					$zoneCodeHolder = $_POST['txtZoneCode'];
				}
				if($zoneName != null) {
					$query_UpdateZone = "UPDATE zone SET zone_name='$zoneName', zone_code='$zoneCode' WHERE zone_id='$id'";
					if(mysqli_query($con,$query_UpdateZone)) {
						echo "<script> alert(\"Zone Updated Successfully\"); </script>";
						header('Refresh:0;url=view_zone.php');
					}
					else {
						$requireErr = "Updating Zone Failed";
					}
				}
				else {
					$requireErr = "* Valid Zone Name is required";
				}
			}
		}
		else {
			header('Location:../index.php');
		}
	}
	else {
		header('Location:../index.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Zone</title>
	<link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
	<?php
		include("../includes/header.inc.php");
		include("../includes/nav_admin.inc.php");
		include("../includes/aside_admin.inc.php");
	?>
	<section>
		<h1>Update Zone</h1>
		<form action="" method="POST" class="form">
			<ul class="form-list">
				<li>
					<div class="label-block"> <label for="zoneName">Zone Name</label> </div>
					<div class="input-box"> <input type="text" id="zoneName" name="txtZoneName" placeholder="Zone Name" value="<?php echo $zoneNameHolder; ?>" required /> </div> <span class="error_message"><?php echo $zoneNameErr; ?></span>
				</li>
				<li>
					<div class="label-block"> <label for="zoneCode">Zone Code</label> </div>
					<div class="input-box"> <input type="text" id="zoneCode" name="txtZoneCode" placeholder="Zone Code" value="<?php echo $zoneCodeHolder; ?>" required /> </div> <span class="error_message"><?php echo $zoneCodeErr; ?></span>
				</li>
				<li>
					<input type="submit" value="Update Zone" class="submit_button" /> <span class="error_message"> <?php echo $requireErr; ?> </span><span class="confirm_message"> <?php echo $confirmMessage; ?> </span>
				</li>
			</ul>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
