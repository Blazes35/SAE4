<?php
namespace App\Popups;
use PDO;

require_once "language.php";
session_start();

if (isset($_POST['formClicked'])) {
    unset($_POST['formClicked']);
    require 'traitements/update_user_info.php';
    $_SESSION['actualiser'] = true;
}

if (isset($_POST['deconnexion'])) {
    unset($_POST['deconnexion']);
    require 'traitements/log_out.php';
    $_SESSION['actualiser'] = true;
    session_destroy();
    header('Location: ../index.php');
    exit();
}

require_once 'loadenv.php';
loadEnv();

$mail = $_SESSION['Mail_Uti'];
$query = $db->prepare("SELECT Prenom_Uti, Nom_Uti, Mail_Uti, Adr_Uti FROM UTILISATEUR WHERE Mail_Uti = ?");
$query->execute([$mail]);
$userInfo = $query->fetch(PDO::FETCH_ASSOC);
?>
<div class="popup">
    <div class="contenuPopup">
        <div style="display:flex;justify-content:space-between;">
            <form method="post">
                <input class="lienPopup" type="submit" value="<?php echo $htmlSeDeconnecter ?>" name="formClicked">
                <input type="hidden" value='info_perso' name="popup">
                <input type="hidden" name="deconnexion">
            </form>
            <form method="post">
                <input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
            </form>
        </div>
        <p class="titrePopup"><?php echo $htmlInformationsPersonelles ?></p>
        <div>
            <p><strong>Prénom:</strong> <?php echo htmlspecialchars($userInfo['Prenom_Uti']); ?></p>
            <p><strong>Nom:</strong> <?php echo htmlspecialchars($userInfo['Nom_Uti']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['Mail_Uti']); ?></p>
            <p><strong>Adresse:</strong> <?php echo htmlspecialchars($userInfo['Adr_Uti']); ?></p>
        </div>
    </div>
</div>