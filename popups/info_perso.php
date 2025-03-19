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
				<input class="lienPopup" type="submit" value="<?php echo $htmlSeDeconnecter?>" name="deconnexion">
                <input type="hidden" value='info_perso' name="popup">
                </form>
            <form method="post">
				<input type="submit" value="" class="boutonQuitPopup">
                <input type="hidden" name="popup" value="">
		    </form>
        </div>
        <p class="titrePopup"><?php echo $htmlInformationsPersonelles?></p>
        <div>
        <?php
        require_once 'traitements/chargement_info_perso.php';
        $result = chargement_info_perso();
        $parsedAddress = parseAddress($result["Adr_Uti"]);
        if (true) {?>
            <form class="formPopup" action='traitements/update_user_info.php' method="post">
                <input type="hidden" value='info_perso' name="popup">
                <div>
                    <label for="new_nom"><?php echo $htmlNomDeuxPoints?></label>
                    <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_nom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo ($result["Nom_Uti"]) ?>">
                </div>
                <div>
                    <label for="new_prenom"><?php echo $htmlPrenomDeuxPoints?></label>
                    <input class="zoneDeTextePopup zoneDeTextePopupFixSize" type="text" name="new_prenom" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" value="<?php echo ($result["Prenom_Uti"]) ?>">
                </div>
                <div>
                    <label><?php echo $htmlAdrPostDeuxPoints?></label>
                    <label><?php echo ($result["Adr_Uti"])?></label>
                </div>
                <div>
                    <label for="rue"><?php echo $htmlRueDeuxPoints?></label>
                    <input class="zoneDeTextePopup" type="text" name="rue" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}"  title="<?php echo $htmlConditionsRue; ?>" value="<?=$parsedAddress['rue'] ?>" required>
                </div>
                <div>
                    <label for="code"><?php echo $htmlCodePostDeuxPoints?></label>
                    <input class="zoneDeTextePopup" type="text" name="code" pattern="^\d{5}$" title="<?php echo $htmlConditionsCodePostal; ?>" value="<?=$parsedAddress['code_postale'] ?>"  required>
                </div>
                <div>
                    <label for="ville"><?php echo $htmlVilleDeuxPoints?></label>
                    <input class="zoneDeTextePopup" type="text" name="ville" pattern="[A-Za-z0-9îçôââêœîâôëçââÿââœçêôïëœœôââôêâçôéâêàôââîââçâœççœâôœêëâôè ]{0,100}" title="<?php echo $htmlConditionsVille; ?>" value="<?=$parsedAddress['ville'] ?>" required>
                </div>
                <div>
                    <label for="pwd">Mot de passe actuel</label>
                    <input class="zoneDeTextePopup" type="password" name="pwd" required>
                </div>
                <div>
                    <?php
                    if (isset($_SESSION['erreur'])) {
                        $erreur = $_SESSION['erreur'];
                        echo '<p class="erreur">'.$erreur.'</p>';
                    }
                    ?>
                </div>
                <button type="submit"><?php echo $htmlModifier ?></button>
            </form>
                    <a href="traitements/del_acc.php"><button><?php echo $htmlSupprimerCompte ?></button></a>
                    <?php if ((isset($_SESSION['isProd']) and $_SESSION['isProd'])) { ?>
                        <a href="./addProfilPicture.php"><button><?php echo 'ajouter une photo de profil' ?></button></a>
                    <?php } ?>
                    <?php
        } else {
            ?><p><?php echo $htmlAucunResultatCompte?></p><?php
        }
        ?>
        </div>
    </div>
</div>