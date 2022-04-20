<?php
include "../../inc/session_test.php";
include "connection.inc";
include "ajax_security.php";

$res = pg_query($con, "insert into driver_notes (dndriver,dndate,dnnote)
values (
" . $_REQUEST["did"] . ",
'" . $_REQUEST["dndate"] . "',
trimwhite('" . $_REQUEST["dnnote"] . "')
) returning dnid
" );

if(pg_result_error($res) != "") {
	echo pg_result_error($res);
} else {
	echo pg_fetch_result($res, 0, 0);
}
?>