<!DOCTYPE html>
<html lang="fr">
<head>
<?php
    require "language.php" ; 
?>
    <title><?php echo $htmlMarque; ?></title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

    <?php
    if(!isset($_SESSION)){
        session_start();
        }

    require_once 'loadenv.php';
    loadEnv();
    $db = dbConnect();

        $bdd=dbConnect();
        $utilisateur=htmlspecialchars($_SESSION["Id_Uti"]);
        
        $filtreCategorie=0;
        if (isset($_POST["typeCategorie"])==true){
            $filtreCategorie=htmlspecialchars($_POST["typeCategorie"]);
        }
    
    ?>

    <div class="customContainer">
        <div class="leftColumn">
			<img class="logo" href="index.php" src="img/logo.png">
            <div class="contenuBarre">
                
            
            <center>
                <p><strong><?php echo $htmlFiltrerParDeuxPoints; ?></strong></p>
                <br>
            </center>
            <?php echo $htmlStatut; ?> 
            <br>
            
            <form action="achats.php" method="post">
                <label>
                    <input type="radio" name="typeCategorie" value="0" <?php if($filtreCategorie==0) echo 'checked="true"';?>> <?php echo $htmlTOUT; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="1" <?php if($filtreCategorie==1) echo 'checked="true"';?>> <?php echo $htmlENCOURS; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="2"<?php if($filtreCategorie==2) echo 'checked="true"';?>> <?php echo $htmlPRETE; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="4" <?php if($filtreCategorie==4) echo 'checked="true"';?>> <?php echo $htmlLIVREE; ?>
                </label>
                <br>
                <label>
                    <input type="radio" name="typeCategorie" value="3" <?php if($filtreCategorie==3) echo 'checked="true"';?>> <?php echo $htmlANNULEE; ?>
                </label>

                <br>
                <br>
                <center>
                    <input type="submit" value="<?php echo $htmlFiltrer; ?>">
                </center>
            </form>

            </div>
        </div>
        <div class="rightColumn">
            <div class="topBanner">
                <div class="divNavigation">
                    <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil?></a>
                    <?php
                        if (isset($_SESSION["Id_Uti"])){
                            echo'<a class="bontonDeNavigation" href="messagerie.php">'.$htmlMessagerie.'</a>';
                            echo'<a class="bontonDeNavigation" href="achats.php">'.$htmlAchats.'</a>';
                        }
                        if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"]==true)){
                            echo'<a class="bontonDeNavigation" href="produits.php">'.$htmlProduits.'</a>';
                            echo'<a class="bontonDeNavigation" href="delivery.php">'.$htmlCommandes.'</a>';
                        }
                        if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"]==true)){
                            echo'<a class="bontonDeNavigation" href="panel_admin.php">'.$htmlPanelAdmin.'</a>';
                        }
                    ?>
                </div>
                <form method="post">
                    <?php
                    if(!isset($_SESSION)){
                        session_start();
                    }
                    if(isset($_SESSION, $_SESSION['tempPopup'])){
                        $_POST['popup'] = $_SESSION['tempPopup'];
                        unset($_SESSION['tempPopup']);
                    }
                    ?>


                    <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])){/*$_SESSION = array()*/; echo($htmlSeConnecter);} else {echo ''.$_SESSION['Mail_Uti'].'';}?>" class="boutonDeConnection">
                    <input type="hidden" name="popup" value=<?php if(isset($_SESSION['Mail_Uti'])){echo '"info_perso"';}else{echo '"sign_in"';}?>>
                
                </form>
            </div>

            <div class="contenuPage">

               
            <?php
				$query='SELECT PRODUCTEUR.Id_Uti, Desc_Statut, Id_Commande, Nom_Uti, Prenom_Uti, Adr_Uti, COMMANDE.Id_Statut FROM COMMANDE INNER JOIN PRODUCTEUR ON COMMANDE.Id_Prod=PRODUCTEUR.Id_Prod INNER JOIN info_producteur ON COMMANDE.Id_Prod=info_producteur.Id_Prod INNER JOIN STATUT ON COMMANDE.Id_Statut=STATUT.Id_Statut WHERE COMMANDE.Id_Uti= :utilisateur';
				if ($filtreCategorie!=0){
					$query=$query.' AND COMMANDE.Id_Statut= :filtreCategorie ;';
				}
				$queryGetCommande = $bdd->prepare($query);
				$queryGetCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
				if ($filtreCategorie!=0){
					$queryGetCommande->bindParam(":filtreCategorie", $filtreCategorie, PDO::PARAM_STR);
				}
				$queryGetCommande->execute();
                $returnQueryGetCommande = $queryGetCommande->fetchAll(PDO::FETCH_ASSOC);
                $iterateurCommande=0;
				if(count($returnQueryGetCommande)==0 and ($filtreCategorie==0)){
                    echo $htmlAucuneCommande;
					?>
					<br>
					<input type="button" onclick="window.location.href='index.php'" value="<?php echo $htmlDecouverteProducteurs; ?>">
					<?php
                }
                elseif(count($returnQueryGetCommande)==0){
                    echo $htmlAucuneCommandeCorrespondCriteres;
                }
                else{
                    while ($iterateurCommande<count($returnQueryGetCommande)){
						$Id_Commande = $returnQueryGetCommande[$iterateurCommande]["Id_Commande"];
						$Nom_Prod = $returnQueryGetCommande[$iterateurCommande]["Nom_Uti"];
						$Nom_Prod = mb_strtoupper($Nom_Prod);
						$Prenom_Prod = $returnQueryGetCommande[$iterateurCommande]["Prenom_Uti"];
						$Adr_Uti = $returnQueryGetCommande[$iterateurCommande]["Adr_Uti"];
						$Desc_Statut = $returnQueryGetCommande[$iterateurCommande]["Desc_Statut"];
						$Desc_Statut = mb_strtoupper($Desc_Statut);
						$Id_Statut = $returnQueryGetCommande[$iterateurCommande]["Id_Statut"];
                        $idUti = $returnQueryGetCommande[$iterateurCommande]["Id_Uti"];


						$total=0;
						$queryGetProduitCommande = $bdd->prepare('SELECT Nom_Produit, Qte_Produit_Commande, Prix_Produit_Unitaire, Nom_Unite_Prix FROM produits_commandes  WHERE Id_Commande = :Id_Commande;');
						$queryGetProduitCommande->bindParam(":Id_Commande", $Id_Commande, PDO::PARAM_STR);
						$queryGetProduitCommande->execute();
						$returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
						$iterateurProduit=0;
						$nbProduit=count($returnQueryGetProduitCommande);

                        if ($nbProduit > 0) {
                            echo '<div class="commande card mb-3">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $htmlCommandeNum . ($iterateurCommande + 1) . " : " . $htmlChez . $Prenom_Prod . ' ' . $Nom_Prod . ' - ' . $Adr_Uti . '</h5>';
                            echo '<p class="card-text">' . $Desc_Statut . '</p>';
                            if ($Id_Statut != 3 && $Id_Statut != 4) {
                                echo '<form action="delete_commande.php" method="post" class="mb-3">';
                                echo '<input type="hidden" name="deleteValeur" value="' . $Id_Commande . '">';
                                echo '<button type="submit" class="btn btn-danger">' . $htmlAnnulerCommande . '</button>';
                                echo '</form>';
                            }
                            echo '<button class="btn btn-primary mb-3" onclick="window.location.href=\'messagerie.php?Id_Interlocuteur=' . $idUti . '\'">' . $htmlEnvoyerMessage . '</button>';
                            while ($iterateurProduit < $nbProduit) {
                                $Nom_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Produit"];
                                $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];
                                $Nom_Unite_Prix = $returnQueryGetProduitCommande[$iterateurProduit]["Nom_Unite_Prix"];
                                $Prix_Produit_Unitaire = $returnQueryGetProduitCommande[$iterateurProduit]["Prix_Produit_Unitaire"];
                                echo '<p class="mb-1">- ' . $Nom_Produit . ' - ' . $Qte_Produit_Commande . ' ' . $Nom_Unite_Prix . ' * ' . $Prix_Produit_Unitaire . '€ = ' . (floatval($Prix_Produit_Unitaire) * floatval($Qte_Produit_Commande)) . '€</p>';
                                $total += (floatval($Prix_Produit_Unitaire) * floatval($Qte_Produit_Commande));
                                $iterateurProduit++;
                            }
                            echo '<div class="text-end"><strong>' . $htmlTotalDeuxPoints . $total . '€</strong></div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        $iterateurCommande++;
                    }
                }
            ?>


            </div>
            <div class="basDePage">
                <form method="post">
                    <input type="submit" value="<?php echo $htmlSignalerDys?>" class="lienPopup">
                    <input type="hidden" name="popup" value="contact_admin">
				</form>
                <form method="post">
                    <input type="submit" value="<?php echo $htmlCGU?>" class="lienPopup">
                    <input type="hidden" name="popup" value="cgu">
				</form>
            </div>
        </div>
    </div>
    <?php require "popups/gestion_popups.php";?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>