<?php
	include("../includes/config.php");
	include("../includes/validate_data.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$zoneName = $zoneCode = $areaID = ""; // Add $areaID variable
			$zoneNameErr = $zoneCodeErr = $requireErr = $confirmMessage = "";
			$zoneNameHolder = $zoneCodeHolder = "";
			if($_SERVER['REQUEST_METHOD'] == "POST") {
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
				if(!empty($_POST['area_id'])) { // Get area_id from form submission
					$areaID = $_POST['area_id'];
				}
				if($zoneName != null && $zoneCode != null && $areaID != null) { // Check if area_id is provided
					$query_addZone = "INSERT INTO zone(zone_name, zone_code, area_id) VALUES('$zoneName','$zoneCode', '$areaID')"; // Include area_id in the query
					if(mysqli_query($con,$query_addZone)) {
						echo "<script> alert(\"Zone Added Successfully\"); </script>";
						header('Refresh:0');
					}
					else {
						$requireErr = "Adding New Zone Failed";
					}
				}
				else {
					$requireErr = "* Valid Zone Name, Zone Code, and Area are required"; // Include Area requirement
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
	<title>Add Zone</title>
	<link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
	<?php
		include("../includes/header.inc.php");
		include("../includes/nav_admin.inc.php");
		include("../includes/aside_admin.inc.php");
	?>
	<section>
		<h1>Add Zone</h1>
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
				<!-- Add a dropdown to select area -->
				<li>
					<div class="label-block"> <label for="areaID">Select Area</label> </div>
					<div class="input-box"> 
						<select name="area_id" id="areaID" required>
							<option value="" disabled selected>Select Area</option>
							<?php
								// Fetch areas from database and populate dropdown
								$query_areas = "SELECT area_id, area_name FROM area";
								$result_areas = mysqli_query($con, $query_areas);
								while($row = mysqli_fetch_assoc($result_areas)) {
									echo "<option value='".$row['area_id']."'>".$row['area_name']."</option>";
								}
							?>
						</select>
					</div>
				</li>
				<li>
					<input type="submit" value="Add Zone" class="submit_button" /> <span class="error_message"> <?php echo $requireErr; ?> </span><span class="confirm_message"> <?php echo $confirmMessage; ?> </span>
				</li>
			</ul>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
