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



function resultSetbyUsername($username){
	$conection=conectarBD();
	$query = "select * from `novedades` limit 10";
	//$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr` limit 10";
	//$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr` where `login` = '".$username."'";
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
	echo "<table border='1'>";
	//echo "<tr><td>Codigo</td><td>Nombres</td><td>Apellidos</td></tr>";
	while ( $row=mysql_fetch_assoc($resultSet)) {
		/*
		echo "Codigo: ".$row[codigo]."<br />";
		echo "Nombres: ".$row[nombres]."<br />";
		echo "Apellidos: ".$row[apellidos]."<br />";
		*/
		echo "<tr>";
		foreach($row as $key => $value){
			
			echo "<td>".$value."</td>";
		
		}
		echo "</tr>";
	}
	echo "</table>";
}

function showResultSet2($resultSet){
	$num_rows=mysql_num_rows($resultSet);
	echo "Num_rows: ".$num_rows."<br />";
	if ($num_rows <= 0) {
    	echo "No rows found, nothing to print so am exiting";
    	exit;
	}
	while ( $row=mysql_fetch_assoc($resultSet)) {
		foreach($row as $key => $value){
			echo ucfirst($key).": ".$value."<br />";
		}
		echo "-------------------------";
		echo "<br />";
	}
}

//buena practica diske------------------------------

function select($query){
	$connection=conectarBD();
	$resultSet=mysql_query($query,$connection) or die (mysql_error());
	$rows=mysql_fetch_assoc($resultSet);
	mysql_close ($connection);
	return $rows;
}

function querybyUsername($username){
	$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr` where `login` = '".$username."'";
	$results=select($query);
	return $results;
}

function muestraTodo(){
	$query = "select `codigo`, `nombres`, `apellidos` from `tm_usr`";
	$results=select($query);
	return $results;
}

function showResults($results){
	$num_rows=sizeof($results);
	echo "Num_rows: ".$num_rows."<br />";
	if ($num_rows <= 0) {
    	echo "No rows found, nothing to print so am exiting";
    	exit;
	}
	
	foreach($results as $key => $value){
		echo ucfirst($key).": ".$value."<br />";
	}	
}


$username='jfernandez';
$resultSet=resultSetbyUsername($username);
//showResultSet($resultSet);
showResultSet2($resultSet);
/*
//echo "<br /><br /><br />";
//$results=querybyUsername($username);
$results=muestraTodo();
showResults($results);
*/
?>