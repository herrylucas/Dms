<?php
include "inc/session_test.php";
include "connection.inc";
include "itemcreators.php";
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
$(document).ready(function () {
	$("#new").click(function () {
		$("#newitem").load("ajax/divs/newtruck.php", {btntext: "TRUCK"}, function () {
			$(this).fadeIn().draggable();
			$("#newfrm input:text").first().focus();
		});
	});

	$(function() {
		$( ".dp" ).datepicker();
	});

	acomplete(".trailers", "ajax/autocompletes/trailers.php?available=true", true, false, false);

	// Validation for elements
	$("#trucks").validate({
		// No error message
		errorPlacement: function (error, element) {
			$(element).prop("title", $(error).text())
		}
	})

	$('[name*="tnumberplate"]').each(function() {
	    $(this).rules('add', {
	        required: true,
	        numberplateTZ: true,
	    });
	});

	$('[name*="tyear"]').each(function() {
		$(this).rules('add', {
			required: true,
			min: 1950,
			max: 2100,
	    });
	});

	$('[name*="troadlicense"]').each(function() {
		$(this).rules('add', {
			required: true,
			dateITA: true,
	    });
	});

	// Match only trailer id for trailer check trnumberplate_NUM_id
	$("input[id^=trnumberplate_][id$=_id]").change(function (e) {
		if ($(this).val() == "") {
			$("#trnumberplate_" + id + "_id").trigger("change")
			return
		}
		//alert($(this).val())
		var id = $(this).attr("id").replace("trnumberplate_","");
		id = id.replace("_id","");
		$.ajax({
			url: "ajax/check_trailer_attached.php",
			type: "POST",
			data: {
				ttrailer_id: $(this).val(),
			},
			success: function (data) {
				if (data != "") {
					var t = data.split("§§§");
					$.confirm('THIS TRAILER IS ALREADY ATTACHED TO TRUCK <b style="color: red">' + t[1] + '</b>.\n\nATTACH IT TO THIS TRUCK?', function (answer) {
						if(!answer) {
							$("#trnumberplate_" + id + "_id").val("");
							$("#trnumberplate_" + id).val("");
							// revert
							$("#trnumberplate_" + id + "_id").trigger("change")
						} else {
							// Use ajax to swap the trailer
							$.ajax({
								url: "ajax/swap_trailer.php",
								type: "POST",
								data: {
									ttrailer_id: $("#trnumberplate_" + id + "_id").val(),
									truckid: id,
								},
								success: function (data) {
									$("#workspace").load("trucks.php");
								},
								error: function (data) {
									$.alert(data)
								}
							})
						}
					})
				}
			}
		});
	});

	check_expiry();
	searchbox();
	excel();
})
</script>
<?php
videohelp("2je9SlJqw7k");
?>
</head>
<body>
<div style="float: right" id="topbuttons">
	<button id="new"><img class="icon" src="icons/new.png" alt=""> Create a new Truck</button>
</div>
<br><br>
<div class="topline">
Current trucks registered on the System:
<br>
<form id="trucks" name="trucks" >
<?php quicksearch("trucks", "tid"); ?>
<table class="tbllist searchtbl" cellpadding=2 cellspacing=0 style="width: 90%">
<tr>
	<th class="hidden" db="tid">ID</th>
	<th db="tnumberplate">Number Plate</th>
	<th db="ttrailer">Trailer Used</th>
	<th db="tenginenumber">Engine Number</th>
	<th db="tchassisnumber">Chassis Number</th>
	<th db="tmake">Truck Make</th>
	<th db="tcomesa">COMESA</th>
	<th db="troadpermit">Road Permit</th>
	<th db="tcarbontax">Carbon Tax</th>
	<th db="tyear" class="centered">Year</th>
	<th></th>
</tr>
<?php
//case when troadlicense <= now() + interval '30 days' then true else false end as expiring
$res = pg_query($con, "select *,
case when trmake is not null and traxles is not null then trnumberplate || ' (' || trmake || ' - ' || traxles || ' axles)' else trnumberplate end as trnumberplate,
false as expiring,
to_char(tcomesa, 'DD/MM/YYYY') as tcomesa,
to_char(troadpermit, 'DD/MM/YYYY') as troadpermit,
to_char(tcarbontax, 'DD/MM/YYYY') as tcarbontax,
case when tcomesa <= now() + interval '30 days' then true else false end as comesaexp,
case when troadpermit <= now() + interval '30 days' then true else false end as roadpermitexp,
case when tcarbontax <= now() + interval '30 days' then true else false end as carbontaxexp
from trucks left join trailers on ttrailer=trid where trucks.company_id=" . $_SESSION["company"] . " order by tnumberplate");
while($row = pg_fetch_assoc($res)) {
	if($row["comesaexp"] == "t") {
		$comesaexp = " expirydate";
	} else {
		$comesaexp = "";
	}
	if($row["roadpermitexp"] == "t") {
		$roadpermitexp = " expirydate";
	} else {
		$roadpermitexp = "";
	}
	if($row["carbontaxexp"] == "t") {
		$carbontaxexp = " expirydate";
	} else {
		$carbontaxexp = "";
	}
	echo "
		<tr class='tbl'>
				<td class='hidden excelid'>" . $row["tid"] . "</td>
				<td>" . uinput("tnumberplate", $row["tid"], $row["tnumberplate"], "trucks", "tnumberplate", "tid", $row["tid"], false,true,75,"centered",null,null,false,true,false) . "</td>
				<td>" . uinput("trnumberplate", $row["tid"], $row["trnumberplate"], "trucks", "ttrailer", "tid", $row["tid"], false,true,250,"trailers",null,true,false,true,false) . "</td>
				<td>" . uinput("tenginenumber", $row["tid"], $row["tenginenumber"], "trucks", "tenginenumber", "tid", $row["tid"], false,true,200,null,null,null,false,true,false) . "</td>
				<td>" . uinput("tchassisnumber", $row["tid"], $row["tchassisnumber"], "trucks", "tchassisnumber", "tid", $row["tid"], false,true,200,null,null,null,false,true,false) . "</td>
				<td>" . uinput("tmake", $row["tid"], $row["tmake"], "trucks", "tmake", "tid", $row["tid"], false,true,null,null,null,null,false,true,false) . "</td>
				<td>" . uinput("tcomesa", $row["tid"], $row["tcomesa"], "trucks", "tcomesa", "tid", $row["tid"], false,false,80,"dp centered" . $comesaexp,null,null,true,true,false) . "</td>
				<td>" . uinput("troadpermit", $row["tid"], $row["troadpermit"], "trucks", "troadpermit", "tid", $row["tid"], false,false,80,"dp centered" . $roadpermitexp,null,null,true,true,false) . "</td>
				<td>" . uinput("tcarbontax", $row["tid"], $row["tcarbontax"], "trucks", "tcarbontax", "tid", $row["tid"], false,false,80,"dp centered" . $carbontaxexp,null,null,true,true,false) . "</td>
				<td class=\"centered\">" . uinput("tyear", $row["tid"], $row["tyear"], "trucks", "tyear", "tid", $row["tid"], true,false,50,"centered",null,null,false,true,false) . "</td>
				<td class='delbtn'>" . delbtn("trucks", "tid", $row["tid"], "trucks.php", null, "#workspace")  . "</td>
		</tr>
	";
}
?>
</table>
</form>
</div>
</body>
</html>