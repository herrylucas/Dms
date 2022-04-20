<?php
include "../../inc/session_test.php";
include "connection.inc";
include "itemcreators.php";
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">

</script>
</head>
<body>
<button onclick="$('#newitem').fadeOut();" class="closebutton">X</button>
<?php
$res = pg_prepare($con, "dlic", "select dname, to_char(dlicenseexp, 'dd/mm/yyyy') as dlicenseexp from drivers where company_id=$1 and dlicenseexp <= now() + interval '30 days' order by dname");
$res = pg_execute($con, "dlic", array($_SESSION['company']));
$count = 1;
if(pg_num_rows($res) > 0) {
	echo "<table class='tbllist' style='text-align: left; width: 500px' >";
	echo "<tr><td colspan='100' class='bold underline'>DRIVER'S LICENSES EXPIRING</td></tr>"; // header
	while($row = pg_fetch_assoc($res)) {
		if($count == 1) {
			echo "<tr><td>";
		}
		echo $row["dname"] . ": <label class='bold' style='color: red;'>" . $row["dlicenseexp"] . "</label>, &nbsp;&nbsp;&nbsp;&nbsp;";
		if($count % 3 == 0) {
			$count = 1;
			// close the row
			echo "</td></tr>"	;
		} else {
			$count++;
		}
	}
	echo "</td></tr></table>";
}
?>
<br>
<?php
$res = pg_prepare($con, "dpp", "select dname, to_char(dpassportexp, 'dd/mm/yyyy') as dpassportexp from drivers where company_id=$1 and dpassportexp <= now() + interval '30 days' order by dname");
$res = pg_execute($con, "dpp", array($_SESSION['company']));
$count = 1;
if(pg_num_rows($res) > 0) {
	echo "<table class='tbllist' style='text-align: left; width: 500px' >";
	echo "<tr><td colspan='100' class='bold underline'>DRIVER'S 	PASSPORTS EXPIRING</td></tr>"; // header
	while($row = pg_fetch_assoc($res)) {
		if($count == 1) {
			echo "<tr><td>";
		}
		echo $row["dname"] . ": <label class='bold' style='color: red;'>" . $row["dpassportexp"] . "</label>, &nbsp;&nbsp;&nbsp;&nbsp;";
		if($count % 3 == 0) {
			$count = 1;
			// close the row
			echo "</td></tr>"	;
		} else {
			$count++;
		}
	}
	echo "</td></tr></table>";
}
?>
<br>
<?php
$res = pg_prepare($con, "tcomesa", "select tnumberplate, to_char(tcomesa, 'dd/mm/yyyy') as tcomesa from trucks where company_id=$1 and tcomesa <= now() + interval '30 days' order by tnumberplate");
$res = pg_execute($con, "tcomesa", array($_SESSION['company']));
$count = 1;
if(pg_num_rows($res) > 0) {
	echo "<table class='tbllist' style='text-align: left; width: 500px' >";
	echo "<tr><td colspan='100' class='bold underline'>COMESA EXPIRING</td></tr>"; // header
	while($row = pg_fetch_assoc($res)) {
		if($count == 1) {
			echo "<tr><td>";
		}
		echo $row["tnumberplate"] . ": <label class='bold' style='color: red;'>" . $row["tcomesa"] . "</label>, &nbsp;&nbsp;&nbsp;&nbsp;";
		if($count % 3 == 0) {
			$count = 1;
			// close the row
			echo "</td></tr>"	;
		} else {
			$count++;
		}
	}
	echo "</td></tr></table>";
}
?>
<br>
<?php
$res = pg_prepare($con, "troadpermit", "select tnumberplate, to_char(troadpermit, 'dd/mm/yyyy') as troadpermit from trucks where company_id=$1 and troadpermit <= now() + interval '30 days' order by tnumberplate");
$res = pg_execute($con, "troadpermit", array($_SESSION['company']));
$count = 1;
if(pg_num_rows($res) > 0) {
	echo "<table class='tbllist' style='text-align: left; width: 500px' >";
	echo "<tr><td colspan='100' class='bold underline'>ROAD PERMITS EXPIRING</td></tr>"; // header
	while($row = pg_fetch_assoc($res)) {
		if($count == 1) {
			echo "<tr><td>";
		}
		echo $row["tnumberplate"] . ": <label class='bold' style='color: red;'>" . $row["tcomesa"] . "</label>, &nbsp;&nbsp;&nbsp;&nbsp;";
		if($count % 3 == 0) {
			$count = 1;
			// close the row
			echo "</td></tr>"	;
		} else {
			$count++;
		}
	}
	echo "</td></tr></table>";
}
?>
<br>
<?php
$res = pg_prepare($con, "tcarbontax", "select tnumberplate, to_char(tcarbontax, 'dd/mm/yyyy') as tcarbontax from trucks where company_id=$1 and tcarbontax <= now() + interval '30 days' order by tnumberplate");
$res = pg_execute($con, "tcarbontax", array($_SESSION['company']));
$count = 1;
if(pg_num_rows($res) > 0) {
	echo "<table class='tbllist' style='text-align: left; width: 500px' >";
	echo "<tr><td colspan='100' class='bold underline'>CARBON TAX EXPIRING</td></tr>"; // header
	while($row = pg_fetch_assoc($res)) {
		if($count == 1) {
			echo "<tr><td>";
		}
		echo $row["tnumberplate"] . ": <label class='bold' style='color: red;'>" . $row["tcarbontax"] . "</label>, &nbsp;&nbsp;&nbsp;&nbsp;";
		if($count % 3 == 0) {
			$count = 1;
			// close the row
			echo "</td></tr>"	;
		} else {
			$count++;
		}
	}
	echo "</td></tr></table>";
}
?>
</body>
</html>