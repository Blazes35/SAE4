<?php
require_once "../loadenv.php";
error_reporting(E_ALL);
ini_set('display_errors', 1);
loadEnv();
if (!isset($_SESSION)) {
    session_start();
}
// Connect to database
$db = dbConnect();
$message = $_POST['message'];
var_dump($_POST);
//die;
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
    $message = $db->quote($message);

    $db->query('CALL broadcast_Producteur(' . $_SESSION["Id_Uti"] . ', ' . $message . ');');
    header("Location: ../messagerie.php");
} else {
    echo "error";
    echo $message;
    var_dump(isset($_SESSION["Id_Uti"]));
    var_dump(isset($message));
}
?>