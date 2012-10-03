<?php
if (!login()) exit;

function login()
{
  global $_POST;
  global $_SESSION;
  global $_GET;

	$ip = $_SERVER['REMOTE_ADDR'];

	if (isset($_GET["a"]) && ($_GET["a"] == 'logout')) $_SESSION["logged_in"] = false;

	if (!isset($_SESSION["logged_in"])) $_SESSION["logged_in"] = false;

	if (!$_SESSION["logged_in"]) {
		$seguridad = "";
		$login = "";
		$password = "";
		if (isset($_POST["seguridad"])) $seguridad = @$_POST["seguridad"];
		
		//establesco la variable de sesion seguridad
		$_SESSION["seguridad"]=$seguridad;

		if (isset($_POST["login"])) $login = strtolower(@$_POST["login"]);

		if (isset($_POST["password"])) $password = strtolower(@$_POST["password"]);

		if (isset($_POST["area"])) $area = @$_POST["area"];
		


// Evalua el numero de intentos en general-->
		if (isset($_SESSION["try"]) && ($_SESSION["try"] == 7)){
				
		//$rs = $conn->Execute('CALL bloq()');
		
		//bloquea ip
			$_SESSION["situacion"]=3;
			
			// Comparamos la ip obtenida contra la base de datos
			$compara_ip = conip($ip);
			
	//		if (isset($compara_ip) && ($compara_ip == 1)){			
				$action=2;
		//	}else{$action=0;}

			$situacion=$_SESSION["situacion"];
//			addmac($login, $seguridad, $ip, $situacion, $action, $area);
			addmac($area, $login, $seguridad, $ip, $situacion, $action);
			include('librerias/accesdenied.php');
			exit;
		}
//-------------------------------------------

//-----<cuando los campos login y password contienen datos>----
		if (($login != "") && ($password != "")) {
			//
			$conn=conectarbd();
			$sql = "select `estado`,`area` from `tm_usr` where `login` = '" .$login ."'";
      		$res = mysql_query($sql, $conn) or die(mysql_error());
			while ($array = mysql_fetch_assoc($res)){
	 		 	$_SESSION["estado"] = $array["estado"];
	 		 	$_SESSION["area"] = $array["area"];
			}	
			$area=$_SESSION["area"];

			if (isset($_SESSION["estado"]) && ($_SESSION["estado"] == 0)){
				$_SESSION["situacion"]=4;
				$situacion=$_SESSION["situacion"];
				$action=1;
				addmac($area, $login, $seguridad, $ip, $situacion, $action);
				include('librerias/accesdenied.php');
				exit;
			}
			
			//
			$conn = mysql_connect("localhost", "root", "casiopea");
			mysql_select_db("intranet");
      		$sql = "select `password` from `tm_usr` where `login` = '" .$login ."'";
      		$res = mysql_query($sql, $conn) or die(mysql_error());
      		$row = mysql_fetch_assoc($res) or $row = array(0 => "");;
      		
			if (isset($row)) reset($row);

			// comparamos la MAC address obtenida con la base de datos

				$compara_seg = conmac($seguridad);

				// Si el flag es 1 continua la verificacion de datos
				if (isset($compara_seg) && ($compara_seg == 1)) {

					//comparamos ip para ver si no esta bloqueada
					$compara_ip = conip($ip);

						//	$tiempito=pruebitaip($ip);			
			?>
			<script>//	alert('<?// echo $tiempito ?>')</script>
			<?
			
			
					if (isset($compara_ip) && ($compara_ip == 0)){			
						$action=2;
						$_SESSION["situacion"]=3;
						$situacion=$_SESSION["situacion"];
						addmac($area, $login, $seguridad, $ip, $situacion, $action);
						include('librerias/accesdenied.php');
						exit;

					}else{
//						$action=3;
						if (isset($compara_ip) && ($compara_ip == 3)){
							act_ip($ip);
						}
					}
				// login correcto

					if (isset($password) && ($password == trim(current($row)))) {
	    		   		$_SESSION["usuario"] = $login;
			    	    $_SESSION["logged_in"] = true;
						$_SESSION["situacion"]=7;
						$situacion=$_SESSION["situacion"];
						addmac($area, $login, $seguridad, $ip, $situacion, $action);
				//----------------		

		    		}else{
						$_SESSION["situacion"]=1;
						$situacion=$_SESSION["situacion"];
						$action=0;
						addmac($area, $login, $seguridad, $ip, $situacion, $action);
						$_SESSION["try"]+=1;

							
						//verificamos que el login digitado exista en la bd
						$compara_login=con_login($login);
						if ($compara_login==1){
							//evaluamos la tabla de log para comparar el ultimo registro de la cuenta
							$eval_log=eval_login($login);
							if ($eval_log==1){	
								$_SESSION["try_log"]+=1;}
								if ($_SESSION["try_log"]>2){
									$_SESSION["situacion"]=4;
									$_SESSION["try_log"]=0;
									$situacion = $_SESSION["situacion"];
									$action=1;
									addmac($area, $login,$seguridad, $ip, $situacion, $action);
									include('librerias/accesdenied.php');
									exit;
								}

							}
?>
						<p><b><font color="#FFFFFF">Lo sentimos, el loggin/password no son correctos.<? echo $_SESSION["try"]?></font></b></p>

<?php
				}
			
				}else{
						if($compara_seg == 2){
							$_SESSION["situacion"]=5;
						}else{
							if($seguridad==''){
								$_SESSION["situacion"]=6;
							}else{
								$_SESSION["situacion"]=2;
							}
						}
				//-----------------------------------------------------------
					$situacion=$_SESSION["situacion"];
					$action=3;
					addmac($area, $login,$seguridad, $ip, $situacion, $action);

					include('librerias/error.php'); exit;
		
				}
		
			}
	}

	if (isset($_SESSION["logged_in"]) && (!$_SESSION["logged_in"])) { ?>
<script>
jQuery(function($){
			$("#seguridad").attr("value",get_macadres());
		});

var cap_macadres=get_macadres();

//alert(cap_macadres);

window.onload = function() 
{ 
  window.document.form1.login.focus();
}  

</script>
 

<center>
<div align="right" style="width:800px; height:200px; background-color:#FFFFFF"><img src="img/banner.jpg" width="800" height="88" usemap="#link0" /><MAP NAME="link0" id="link0"><AREA SHAPE=CIRCLE COORDS="60,56,47" onclick="mouseDown()"></MAP>

	<div id=fondo style="width:800px; height:400px; background-color:#CCCCCC">

		<div id="login" align="center" style="margin:50px auto; width:300px; background-color:#CC3333; color:#FFFFFF">

			</br><b>Por favor Ingresar su Login y su Password:</b>

			<form name="form1" id="form1" action="index.php" method="post">
			<table  style="text-align:left"id="tablelogin" width="300" border="0" cellpadding="4" cellspacing="1" class="bd">

				<tr>
					<td>Login</td>
					<td><input id="login" name="login" type="text" value="<?php echo $login ?>" size="22"></td>
				</tr>
	
			    <tr>
					<td>Password</td>	
					<td><input type="password" size="24" name="password" value="<?php echo $password ?>"></td>
				</tr>
	
			    <tr>
					<td><input type="submit" name="action" value="Ingresar"></td>
				</tr>

				</table>
				<input type="hidden" name="seguridad" id="seguridad">
			</form>

		</div>
	</div>
</div>

<?php
	}


  if (!isset($_SESSION["logged_in"])) $_SESSION["logged_in"] = false;

  return $_SESSION["logged_in"];

} ?>