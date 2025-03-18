<?php
require 'loadenv.php';
loadEnv();
$db = dbConnect();
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
  
  $db->query('CALL broadcast_admin(' . $_SESSION["Id_Uti"] . ', \'' . $message . '\');');
} else {
  
  $db->query('CALL broadcast_admin(0 , \''. $_POST["mail"]. $message . '\');');
}

