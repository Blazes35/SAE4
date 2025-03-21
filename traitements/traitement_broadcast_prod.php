<?php
require_once "../loadenv.php";
loadEnv();
if (!isset($_SESSION)) {
    session_start();
}
// Connect to database
$db = dbConnect();
$message = $_POST['message'];
if (isset($_SESSION["Id_Uti"]) && isset($message)) {
    $message = $db->quote($message);

    $db->query('CALL broadcast_Producteur(' . $_SESSION["Id_Uti"] . ', ' . $message . ');');
    header("Location: ../messagerie.php");
} else {
    echo"";
}
?>