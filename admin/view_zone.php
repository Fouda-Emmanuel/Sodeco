<?php
	include("../includes/config.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$query_selectZone = "SELECT * FROM zone";
			$result_selectZone = mysqli_query($con,$query_selectZone);
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				if(isset($_POST['chkId'])) {
					$chkId = $_POST['chkId'];
					foreach($chkId as $id) {
						$query_deleteZone = "DELETE FROM zone WHERE zone_id='$id'";
						$result = mysqli_query($con,$query_deleteZone);
					}
					if(!$result) {
						echo "<script> alert(\"You cannot delete this zone as it is assigned to sectors.\"); </script>";
						header('Refresh:0');
					}
					else {
						echo "<script> alert(\"Zone deleted successfully.\"); </script>";
						header('Refresh:0');
					}
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
	<title>View Zone</title>
	<link rel="stylesheet" href="../includes/main_style.css">
	<script language="JavaScript">
	function toggle(source) {
		checkboxes = document.getElementsByName('chkId[]');
		for(var i=0, n=checkboxes.length;i<n;i++) {
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
		<h1>View Zone</h1>
		<form action="" method="POST" class="form">
		<table class="table_displayData">
			<tr>
				<th> <input type="checkbox" onClick="toggle(this)" /> </th>
				<th>Sr. No.</th>
				<th>Zone Name</th>
				<th>Action</th>
			</tr>
			<?php $i=1; while($row_selectZone = mysqli_fetch_array($result_selectZone)) { ?>
			<tr>
				<td> <input type="checkbox" name="chkId[]" value="<?php echo $row_selectZone['zone_id']; ?>" /> </td>
				<td> <?php echo $i; ?> </td>
				<td> <?php echo $row_selectZone['zone_name']; ?> </td>
				<td> <a href="edit_zone.php?id=<?php echo $row_selectZone['zone_id']; ?>"><img src="../images/edit.png" alt="edit" /></a> </td>
			</tr>
			<?php $i++; } ?>
		</table>
		<input type="submit" value="Delete" class="submit_button"/>
		<a href="add_zone.php"><input type="button" value="+ Add Zone" class="submit_button"/></a>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
