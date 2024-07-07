<?php
	include("../includes/config.php");
	session_start();
	if(isset($_SESSION['admin_login'])) {
		if($_SESSION['admin_login'] == true) {
			$query_selectSector = "SELECT * FROM sector";
			$result_selectSector = mysqli_query($con,$query_selectSector);
			if($_SERVER['REQUEST_METHOD'] == "POST") {
				if(isset($_POST['chkId'])) {
					$chkId = $_POST['chkId'];
					foreach($chkId as $id) {
						$query_deleteSector = "DELETE FROM sector WHERE sector_id='$id'";
						$result = mysqli_query($con,$query_deleteSector);
					}
					if(!$result) {
						echo "<script> alert(\"You cannot delete this sector as it is assigned to products.\"); </script>";
						header('Refresh:0');
					}
					else {
						echo "<script> alert(\"Sector deleted successfully.\"); </script>";
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
	<title>View Sector</title>
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
		<h1>View Sector</h1>
		<form action="" method="POST" class="form">
		<table class="table_displayData">
			<tr>
				<th> <input type="checkbox" onClick="toggle(this)" /> </th>
				<th>Sr. No.</th>
				<th>Sector Name</th>
				<th>Action</th>
			</tr>
			<?php $i=1; while($row_selectSector = mysqli_fetch_array($result_selectSector)) { ?>
			<tr>
				<td> <input type="checkbox" name="chkId[]" value="<?php echo $row_selectSector['sector_id']; ?>" /> </td>
				<td> <?php echo $i; ?> </td>
				<td> <?php echo $row_selectSector['sector_name']; ?> </td>
				<td> <a href="edit_sector.php?id=<?php echo $row_selectSector['sector_id']; ?>"><img src="../images/edit.png" alt="edit" /></a> </td>
			</tr>
			<?php $i++; } ?>
		</table>
		<input type="submit" value="Delete" class="submit_button"/>
		<a href="add_sector.php"><input type="button" value="+ Add Sector" class="submit_button"/></a>
		</form>
	</section>
	<?php
		include("../includes/footer.inc.php");
	?>
</body>
</html>
