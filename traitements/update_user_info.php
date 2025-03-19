<?php
require_once '../loadenv.php';
loadEnv();
$db = dbConnect();
session_start();



if (isset($_POST['confirm_update']) && $_POST['confirm_update'] == 'oui') {


    if (isset($_POST['new_nom'], $_POST['new_prenom'], $_POST['rue'], $_POST['code'], $_POST['ville'], $_POST['pwd'])) {




        $adr = $_POST['rue'] . ", " . $_POST['code'] . " " . mb_strtoupper($_POST['ville']);
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
                if ($stmt->rowCount() > 0) {
                    error_log("Update successful for user: " . $_SESSION['Mail_Uti']);
                } else {
                    error_log("No rows updated for user: " . $_SESSION['Mail_Uti']);
                }
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
} else {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/deleteAcc.css">
    </head>
    <body>
    <div class="container">
        <form method="post" action="update_user_info.php">
            <p>Voulez-vous vraiment modifier vos informations ?</p>
            <input type="hidden" name="confirm_update" value="oui">
            <input type="text" name="new_nom" value="<?php echo htmlspecialchars($_POST['new_nom']); ?>">
            <input type="text" name="new_prenom" value="<?php echo htmlspecialchars($_POST['new_prenom']); ?>">
            <input type="text" name="rue" value="<?php echo htmlspecialchars($_POST['rue']); ?>">
            <input type="text" name="code" value="<?php echo htmlspecialchars($_POST['code']); ?>">
            <input type="text" name="ville" value="<?php echo htmlspecialchars($_POST['ville']); ?>">
            <input type="text" name="pwd" value="<?php echo htmlspecialchars($_POST['pwd']); ?>">
            <button type="submit" class="delete-btn">Oui, modifier mes informations</button>
        </form>
        <form method="get" action="../index.php">
            <button type="submit" class="home-btn">Non, retourner à l'accueil</button>
        </form>
    </div>
    </body>
    </html>
    <?php
}
?>