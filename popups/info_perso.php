<?php
session_start();
    require "language.php" ;
    function parseAddress($address){
        // Trim the address
        $address = trim($address);

        // Common pattern for French addresses: street, postal code CITY
        if (preg_match('/^(.*?)(?:,\s*)?(\d{5})\s+(.+)$/i', $address, $matches)) {
            return [
                'rue' => trim($matches[1]),
                'code_postale' => trim($matches[2]),
                'ville' => trim($matches[3])
            ];
        }
        return [
            'rue' => $address,
            'code_postale' => $address,
            'ville' => $address
        ];
    }

?>
<?php
        if (isset($_POST['formClicked'])){
            unset($_POST['formClicked']);
            require 'traitements/update_user_info.php';
            $_SESSION['actualiser'] = true;
        }
        if(isset($_POST['deconnexion'])){
            unset($_POST['deconnexion']);
            require 'traitements/log_out.php';
            $_SESSION['actualiser'] = true;
        }
        ?>
    <div class="popup">
    <div class="contenuPopup">
        <div style="display:flex;justify-content:space-between;">
            <form method="post">
				<input class="lienPopup" type="submit" value="<?php echo htmlspecialchars($htmlSeDeconnecter);?>" name="deconnexion">
                <input type="hidden" value='info_perso' name="popup">
                </form>
            <form method="post">
				<input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
		    </form>
        </div>
        <p class="titrePopup"><?php echo htmlspecialchars($htmlInformationsPersonelles); ?></p>
        <div>
        <?php
        require_once 'traitements/chargement_info_perso.php';
        $result = chargement_info_perso();
        $parsedAddress = parseAddress($result["Adr_Uti"]);
        if (true) {?>
            <form class="formPopup" action='traitements/update_user_info.php' method="post">
                <input type="hidden" value='info_perso' name="popup">
                <div class="mb-3">
                    <label for="new_nom" class="form-label"><?php echo htmlspecialchars($htmlNomDeuxPoints); ?></label>
                    <input class="form-control" type="text" name="new_nom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo ($result["Nom_Uti"]) ?>">
                </div>
                <div class="mb-3">
                    <label for="new_prenom" class="form-label"><?php echo htmlspecialchars($htmlPrenomDeuxPoints); ?></label>
                    <input class="form-control" type="text" name="new_prenom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo ($result["Prenom_Uti"]) ?>">
                </div>
                <div class="mb-3">
                    <label for="rue" class="form-label"><?php echo htmlspecialchars($htmlRueDeuxPoints); ?></label>
                    <input class="form-control" type="text" name="rue" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsRue; ?>" value="<?=$parsedAddress['rue'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label"><?php echo htmlspecialchars($htmlCodePostDeuxPoints); ?></label>
                    <input class="form-control" type="text" name="code" pattern="^\d{5}$" title="<?php echo $htmlConditionsCodePostal; ?>" value="<?=$parsedAddress['code_postale'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="ville" class="form-label"><?php echo htmlspecialchars($htmlVilleDeuxPoints); ?></label>
                    <input class="form-control" type="text" name="ville" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsVille; ?>" value="<?=$parsedAddress['ville'] ?>" required>
                </div>
                <div class="mb-3">
                    <label for="pwd" class="form-label">Mot de passe actuel</label>
                    <input class="form-control" type="password" name="pwd" required>
                </div>
                <div class="mb-3">
                    <?php
                    if (isset($_SESSION['erreur'])) {
                        $erreur = $_SESSION['erreur'];
                        echo '<p class="text-danger">'.$erreur.'</p>';
                    }
                    ?>
                </div>
                <button type="submit" class="btn btn-primary"><?php echo htmlspecialchars($htmlModifier); ?></button>
            </form>
                    <a href="traitements/del_acc.php"><button type="button" class="btn btn-danger" ><?php echo htmlspecialchars($htmlSupprimerCompte); ?></button></a>
                    <?php if ((isset($_SESSION['isProd']) and $_SESSION['isProd'])) { ?>
                        <a href="./addProfilPicture.php"><button><?php echo htmlspecialchars('ajouter une photo de profil'); ?></button></a>
                    <?php } ?>
                    <?php
        } else {
            ?><p><?php echo htmlspecialchars($htmlAucunResultatCompte); ?></p><?php
        }
        ?>
        </div>
    </div>
</div>