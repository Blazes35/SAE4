<?php
require "language.php" ;
require_once "loadenv.php";
loadEnv();



$pwd1 = $_POST['pwd1'];
$pwd2 = $_POST['pwd2'];

if ($pwd1 == $pwd2 && $pwd1 !== null) {

    $db = dbConnect();
    if(!isset($_SESSION)){
        session_start();
        }
    // Vérif d'abord si l'adr mail existe bien dans la table utilisateur
    $checkEmailQuery = "SELECT COUNT(*) AS count FROM UTILISATEUR WHERE Mail_Uti = :mail";
    $checkEmailStmt = $db->prepare($checkEmailQuery);
    $checkEmailStmt->bindParam(':mail', $_SESSION["mailTemp"]);
    $checkEmailStmt->execute();
    $emailCount = $checkEmailStmt->fetch(PDO::FETCH_ASSOC)['count'];

    if ($emailCount > 0) {  
        $update="UPDATE UTILISATEUR SET Pwd_Uti = '".$pwd1."' WHERE Mail_Uti = '".$_SESSION["mailTemp"] ."';";
        echo ($update);
        $db->exec($update);
        header('Location: pwd.php?message==$'.$htmlMessageUrlMdpMajOk);

    } else {
        header('Location: pwd.php?message='.$htmlMessageUrlAdrInvalide);
    }
}
?>