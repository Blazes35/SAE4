<?php
require_once '../loadenv.php';
loadEnv();

if (isset($_POST['new_nom'], $_POST['new_prenom'], $_POST['rue'], $_POST['code'], $_POST['ville'], $_POST['pwd'])) {
    $adr = $_POST['rue'] .", ". $_POST['code']. " ".mb_strtoupper($_POST['ville']);

    if (!isset($_SESSION)) {
        session_start();
    }

    if ($db) {
        $update = "UPDATE UTILISATEUR SET Nom_Uti = :new_nom, Prenom_Uti = :new_prenom, Adr_Uti = :adr, Pwd_Uti = :pwd WHERE Mail_Uti = :mail_uti";

        $stmt = $db->prepare($update);
        $stmt->bindParam(':new_nom', $_POST['new_nom']);
        $stmt->bindParam(':new_prenom', $_POST['new_prenom']);
        $stmt->bindParam(':adr', $adr);
        $stmt->bindParam(':pwd', $_POST['pwd']);
        $stmt->bindParam(':mail_uti', $_SESSION['Mail_Uti']);

        try {
            $stmt->execute();
            error_log("Update successful for user: " . $_SESSION['Mail_Uti']);
        } catch (Exception $e) {
            error_log("Update failed: " . $e->getMessage());
        }
    } else {
        error_log("Database connection failed.");
    }

    header('Location: ../index.php');
} else {
    header('Location: ../index.php');
}
?>