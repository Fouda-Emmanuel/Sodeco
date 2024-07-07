<?php
	include("../includes/config.php");
	include("../includes/validate_data.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$sectorName = $sectorCode = $zoneId = "";
			$sectorNameErr = $sectorCodeErr = $requireErr = $confirmMessage = "";
			$sectorNameHolder = $sectorCodeHolder = "";
			if($_SERVER['REQUEST_METHOD'] == "POST") {
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
				if(!empty($_POST['zoneId'])) {
					$zoneId = $_POST['zoneId'];
				}
				if($sectorName != null && $zoneId != null) {
					$query_addSector = "INSERT INTO sector(sector_name, sector_code, zone_id) VALUES('$sectorName', '$sectorCode', '$zoneId')";
					if(mysqli_query($con,$query_addSector)) {
						echo "<script> alert(\"Sector Added Successfully\"); </script>";
						header('Refresh:0');
					}
					else {
						$requireErr = "Adding New Sector Failed";
					}
				}
				else {
					$requireErr = "* Valid Sector Name and Zone are required";
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
	<title>Add Sector</title>
	<link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
	<?php
		include("../includes/header.inc.php");
		include("../includes/nav_admin.inc.php");
		include("../includes/aside_admin.inc.php");
	?>
	<section>
		<h1>Add Sector</h1>
		<form action="" method="POST" class="form">
			<ul class="form-list">
				<li>
					<div class="label-block"> <label for="sectorName">Sector Name</label> </div>
					<div class="input-box"> <input type="text" id="sectorName" name="txtSectorName" placeholder="Sector Name" value="<?php echo $sectorNameHolder; ?>" required /> </div> <span class="error_message"><?php echo $sectorNameErr; ?></span>
				</li>
				<li>
					<div class="label-block"> <label for="sectorCode">Sector Code</label> </div>
					<div class="input-box"> <input type="text" id="sectorCode" name="txtSectorCode" placeholder="Sector Code" value="<?php echo $sectorCodeHolder; ?>" required /> </div> <span class="error_message"><?php echo $sectorCodeErr; ?></span>
				</li>
				<li>
					<div class="label-block"> <label for="zoneId">Zone</label> </div>
					<div class="input-box"> 
						<select id="zoneId" name="zoneId" required>
							<option value="">Select Zone</option>
							<!-- Fetch zones from the database and populate the options -->
							<?php
								$query_zones = "SELECT zone_id, zone_name FROM zone";
								$result_zones = mysqli_query($con, $query_zones);
								while($row = mysqli_fetch_assoc($result_zones)) {
									echo "<option value='{$row['zone_id']}'>{$row['zone_name']}</option>";
								}
							?>
						</select>
					</div>
				</li>
				<li>
					<input type="submit" value="Add Sector" class="submit_button" /> <span class="error_message"> <?php echo $requireErr; ?> </span><span class="confirm_message"> <?php echo $confirmMessage; ?> </span>
				</li>
			</ul>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
