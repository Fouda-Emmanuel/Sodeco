<?php
	include("../includes/config.php");
	include("../includes/validate_data.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$sectorName = $sectorCode = "";
			$sectorNameErr = $sectorCodeErr = $requireErr = $confirmMessage = "";
			$sectorNameHolder = $sectorCodeHolder = "";
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				$id = $_GET['id'];
				if(!empty($_POST['txtSectorName'])) {
					$result = validate_name($_POST['txtSectorName']);
					if($result == 1) {
						$sectorName = $_POST['txtSectorName'];
					}
					else{
						$sectorNameErr = $result;
					}
				}
				if(!empty($_POST['txtSectorCode'])) {
					$sectorCode = $_POST['txtSectorCode'];
					$sectorCodeHolder = $_POST['txtSectorCode'];
				}
				if($sectorName != null) {
					$query_UpdateSector = "UPDATE sector SET sector_name='$sectorName', sector_code='$sectorCode' WHERE sector_id='$id'";
					if(mysqli_query($con,$query_UpdateSector)) {
						echo "<script> alert(\"Sector Updated Successfully\"); </script>";
						header('Refresh:0;url=view_sector.php');
					}
					else {
						$requireErr = "Updating Sector Failed";
					}
				}
				else {
					$requireErr = "* Valid Sector Name is required";
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
	<title>Update Sector</title>
	<link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
	<?php
		include("../includes/header.inc.php");
		include("../includes/nav_admin.inc.php");
		include("../includes/aside_admin.inc.php");
	?>
	<section>
		<h1>Update Sector</h1>
		<form action="" method="POST" class="form">
			<ul class="form-list">
				<li>
					<div class="label-block"> <label for="sectorName">Sector Name</label> </div>
					<div class="input-box"> <input type="text" id="sectorName" name="txtSectorName" placeholder="Sector Name" value="<?php echo $sectorNameHolder; ?>" required /> </div> <span class="error_message"><?php echo $sectorNameErr; ?></span>
				</li>
				<li>
					<div class="label-block">
					<label for="sectorCode">Sector Code</label> </div>
					<div class="input-box"> <input type="text" id="sectorCode" name="txtSectorCode" placeholder="Sector Code" value="<?php echo $sectorCodeHolder; ?>" required /> </div> <span class="error_message"><?php echo $sectorCodeErr; ?></span>
				</li>
				<li>
					<input type="submit" value="Update Sector" class="submit_button" /> <span class="error_message"> <?php echo $requireErr; ?> </span><span class="confirm_message"> <?php echo $confirmMessage; ?> </span>
				</li>
			</ul>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
