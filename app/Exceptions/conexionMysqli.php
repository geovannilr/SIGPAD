<?php
$mysqli = new mysqli("localhost", "siscareeisi", 'SISe1S1%8102CARE', "siscareeisi");
if ($mysqli->connect_errno) {
	echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
//echo $mysqli->host_info . "\n";

/*$mysqli = new mysqli("127.0.0.1", "cloudvps_sercomr", "Sercom2018", "cloudvps_sercomreset2018", 3306);
if ($mysqli->connect_errno) {
echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}*/

?>
