<?php
include "../../inc/session_test.php";
include "connection.inc";
include "itemcreators.php";
?>
<!DOCTYPE html>
<html>
<head>
<script type="text/javascript">
$(document).ready(function () {
	$("#note").keyup(function () {
		if ($(this).val() != "") {
			// active add
			$("#add").prop("disabled", false).addClass("disabled");
		} else {
			$("#add").prop("disabled", true).removeClass("disabled");
		}
	});

	$("#newfrm").validate({
		// No error message
		errorPlacement: function (error, element) {
			$(element).prop("title", $(error).text())
		},
		rules: {
			number: {
				required: {
					depends: function(){
						$(this).val($.trim($(this).val()));
						return true;
        			}
        		},
				phoneTZ: true,
				minlength: 13,
			}
		},
	});

	$('.rowdrvphone').each(function() {
	    $(this).rules('add', {
	        required: true,
	        phoneTZ: true,
	    });
	});

	$(".rowdrvphone").change(function () {
		if ($(this).valid() == false) {
			$(this).focus().select();
		} else {
			$(this).removeClass("error");
			updatevalue("driver_phones", "dpphoneno","'" + $(this).val() + "'", "dpid", $(this).attr("id").replace("dpphoneno_","").replace("_id",""), false, true, this, false,false);
			$("#workspace").load("drivers.php");
		}
	})

	$("#add").click(function (e) {
		e.preventDefault();
		if ($("#newfrm").valid() == true) {
			$.ajax({
				url: "ajax/inserts/newdrivernote.php",
				type: "POST",
				data: {
					dndate: $("#date").val(),
                    dnnote: $("#note").val(),
					did: <?php echo $_REQUEST['did']; ?>,
					token: '<?php echo $_SESSION['atoken']; ?>',
				},
				success: function (data) {
					if ($.isNumeric(data)) {
						//$.alert('NEW CUSTOMER CREATED: ID ' + data);
						$("#newitem").load("ajax/divs/newdrivernote.php", {did: <?php echo $_REQUEST['did']; ?>});
					} else {
						$.alert('<?php echo QUERYERROR; ?>' + data);
					}
				},
				error: function (data) {
					$.alert('<?php echo AJAXERROR; ?>');
				},
			})
		}
	})

    $("#date").datepicker()

	tablerows();
})
</script>
<?php
$res = pg_query($con, "select dname from drivers where did=" . $_REQUEST["did"]);
$res2 = pg_query($con, "select dnid, dnnote, to_char(dndate, 'DD/MM/YYYY') as dndate from driver_notes where dndriver=" . $_REQUEST["did"] . " order by dndate desc");
?>
</head>
<body>
<button onclick="$('#newitem').fadeOut();" class="closebutton">X</button>
<form id="newfrm">
	<table class="tbllist" cellpadding=2 cellspacing=0>
		<tr>
			<td colspan="3" style="text-align: center; font-weight: bold; text-decoration: underline">
				Add notes for <?php echo pg_fetch_result($res, 0, 0); ?>:
			</td>
		</tr>
        <tr>
			<td style="vertical-align: top; text-align: left">Date:<br><input type="text" name="date" id="date" style="width: 100px" autocomplete=""></td>
            <td style="vertical-align: top; text-align: left">Note:<br><textarea name="note" id="note" style="width: 500px"></textarea></td>
			<td style="width: 10px;"><button id="add" disabled="true">ADD</button></td>
		</tr>
		<?php
		if(pg_num_rows($res2) > 0) {
			while($row = pg_fetch_assoc($res2)) {
				echo "<tr class='tbl'>
                <td style='vertical-align: top'>" . $row["dndate"] . "</td>
                <td style='width: 500px; text-align: left'>" . $row["dnnote"] . "</td>
				<td class='smallbtn'>" . delbtn("driver_notes", "dnid", $row["dnid"], "ajax/divs/newdrivernote.php?did=" . $_REQUEST["did"], "text", "#newitem")  . "</td>";
			}
		}
		?>
		</tr>
	</table>
</form>
<div style="text-align: center; width: 100%">
<input type="hidden" name="did" id="did" value="<?php echo $_REQUEST['did']; ?>">
</div>
</body>
</html>