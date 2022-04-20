<?php
// Always escape all quotes in $_REQUEST except when we are passing an array of checkboxes
/*function contains_array($array){
    foreach($array as $value){
        if(is_array($value)) {
          return true;
        }
    }
    return false;
}*/

/*if(contains_array($_REQUEST) == false) {
	$escapedGet = array_map('pg_escape_string', $_REQUEST);
	$_REQUEST = $escapedGet;
}*/

function ip_is_private ($ip) {
    $pri_addrs = array (
                      '10.0.0.0|10.255.255.255', // single class A network
                      '172.16.0.0|172.31.255.255', // 16 contiguous class B network
                      '192.168.0.0|192.168.255.255', // 256 contiguous class C network
                      '169.254.0.0|169.254.255.255', // Link-local address also refered to as Automatic Private IP Addressing
                      '127.0.0.0|127.255.255.255' // localhost
                     );

    $long_ip = ip2long ($ip);
    if ($long_ip != -1) {

        foreach ($pri_addrs AS $pri_addr) {
            list ($start, $end) = explode('|', $pri_addr);

             // IF IS PRIVATE
             if ($long_ip >= ip2long ($start) && $long_ip <= ip2long ($end)) {
                 return true;
             }
        }
    }

    return false;
}

// DB Connection based on location local dev port 5433
if (ip_is_private($_SERVER['REMOTE_ADDR'])) {
	$con = pg_connect("host=127.0.0.1 port=5433 dbname=mtl user=mtl password=mtl2016");
} else {
	$con = pg_connect("host=127.0.0.1 port=9999 dbname=mtl user=mtl password=@mtl_2016!dbOnE sslmode=require");
}
?>
