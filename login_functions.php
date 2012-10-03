<?
function conectarBD(){
	$usuarioBD="root";
	$passworBD="c2rb2r4";
	$baseDatos="intranet";
	$servidor="localhost";

	$connection = mysql_connect($servidor, $usuarioBD, $passworBD);
    mysql_select_db($baseDatos);
	return $connection;
}


function checkInjection($username,$password){
	$username = stripslashes($myusername);
	$password = stripslashes($mypassword);
	$username = mysql_real_escape_string($myusername);
	$password = mysql_real_escape_string($mypassword);
}


function ResultSetbyUsername($username){
	$conection=conectarBD();
	//$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr` limit 10";
	$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr` where `login` = '".$username."'";
	$resultSet = mysql_query($query, $conection) or die(mysql_error());
	return $resultSet;
}


function showResultSet($resultSet){
	$num_rows=mysql_num_rows($resultSet);
	echo "Num_rows: ".$num_rows."<br />";
	if ($num_rows <= 0) {
    	echo "No rows found, nothing to print so am exiting";
    	exit;
	}
	
	foreach(mysql_fetch_assoc($resultSet) as $key => $value){
		echo ucfirst($key).": ".$value."<br />";
	}
}


$username='mruiz';
$resultSet=ResultSetbyUsername($username);
showResultSet($resultSet);

?>