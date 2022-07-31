<?php
session_start();

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$_SESSION['usuario_cuenta'] = $usuario;
$_SESSION['contrasena_cuenta'] = $password;
?>
<html lang="es">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <link rel="icon" href="ppal/images/favicon.ico">
  <title>Redirect</title>
</head>
<body>
<?php
require_once('routeros_api.class.php');
$API = new routeros_api();
$API->debug = false;

$meses = [['ing' => 'jan', 'num' => '01'], ['ing' => 'feb', 'num' => '02'], ['ing' => 'mar', 'num' => '03'], ['ing' => 'apr', 'num' => '04'], ['ing' => 'may', 'num' => '05'], ['ing' => 'jun', 'num' => '06'], ['ing' => 'jul', 'num' => '07'], ['ing' => 'aug', 'num' => '08'], ['ing' => 'sep', 'num' => '09'], ['ing' => 'oct', 'num' => '10'], ['ing' => 'nov', 'num' => '11'], ['ing' => 'dec', 'num' => '12']];


if($API->connect('192.168.0.1','userapi','userapi',8728)){
  $API->write('/ip/hotspot/user/getall',true);
  $READ = $API->read(false);
  $ARRAY = $API->parse_response($READ);
  if(count($ARRAY)>0){
  foreach ($ARRAY as $key => $value) {
    if($key != 0 && isset($value['password'])){
    if($usuario == $value['name'] && $password == $value['password']){
      header("location: /ppal");
      $API->disconnect();
      break;
    }
    header("location: elogin.html");
  }
  }
  }else{  //el servidor API esta of line
       echo '<img src="icon_led_grey.png" />&nbsp;'.$ARRAY['!trap'][0]['message'];
        }
  }else{//la conexion ha fallado
    echo "<font color='#ff0000'>La conexion ha fallado. Verifique si el Api esta activo.</font>";
      }
      $API->disconnect();



?>
