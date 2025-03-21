<!DOCTYPE html>
<html lang="fr">
<head>
    <?php
    require "loadenv.php";
    loadEnv();
    $db = dbConnect();
    require "language.php";
    $htmlMarque = "L'Étal en Ligne";
    $htmlFrançais = "Français";
    $htmlAnglais = "English";
    $htmlEspagnol = "Español";
    $htmlAllemand = "Deutch";
    $htmlRusse = "русский";
    $htmlChinois = "中國人";
    ?>
    <title> <?php echo $htmlMarque; ?> </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/style_general.css">
    <link rel="stylesheet" type="text/css" href="css/popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<?php
if (!isset($_SESSION)) {
    session_start();
}
$rechercheVille = isset($_GET["rechercheVille"]) ? htmlspecialchars($_GET["rechercheVille"]) : "";
$_GET["categorie"] = isset($_GET["categorie"]) ? $_GET["categorie"] : "Tout";
$utilisateur = isset($_SESSION["Id_Uti"]) ? htmlspecialchars($_SESSION["Id_Uti"]) : -1;
$rayon = isset($_GET["rayon"]) ? $rayon = htmlspecialchars($_GET["rayon"]) : 100;
$tri = isset($_GET["tri"]) ? htmlspecialchars($_GET["tri"]) : $tri = "nombreDeProduits";
if (isset($_SESSION["language"]) == false) {
    $_SESSION["language"] = "fr";
}

function latLongGps($url) {
    try {
        // Initialize cURL with error checking
        $ch = curl_init();
        if (!$ch) {
            error_log("Failed to initialize cURL");
            return [0, 0];
        }

        // Set the URL
        curl_setopt($ch, CURLOPT_URL, $url);

        // Configure proxy if needed - consider making these conditional
        curl_setopt($ch, CURLOPT_PROXY, 'proxy.univ-lemans.fr');
        curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, true);

        // Other cURL options
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "LEtalEnLigne/1.0");
        curl_setopt($ch, CURLOPT_REFERER, "http://proxy.univ-lemans.fr:3128");

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            error_log('Erreur cURL : ' . curl_error($ch));
            curl_close($ch);
            return [0, 0];
        }

        // Process the response
        $data = json_decode($response);

        if (!empty($data) && is_array($data) && isset($data[0])) {
            $latitude = $data[0]->lat;
            $longitude = $data[0]->lon;
            curl_close($ch);
            return [$latitude, $longitude];
        }

        curl_close($ch);
        return [0, 0];
    } catch (Exception $e) {
        error_log("Exception in latLongGps: " . $e->getMessage());
        return [0, 0];
    }
}


/*---------------------------------------------------------------*/
/*
    Titre : Calcul la distance entre 2 points en km

    URL   : https://phpsources.net/code_s.php?id=1091
    Auteur           : sheppy1
    Website auteur   : https://lejournalabrasif.fr/qwanturank-concours-seo-qwant/
    Date édition     : 05 Aout 2019
    Date mise à jour : 16 Aout 2019
    Rapport de la maj:
    - fonctionnement du code vérifié
*/
/*---------------------------------------------------------------*/

function distance($lat1, $lng1, $lat2, $lng2, $miles = false)
{
    $pi80 = M_PI / 180;
    $lat1 *= $pi80;
    $lng1 *= $pi80;
    $lat2 *= $pi80;
    $lng2 *= $pi80;

    $r = 6372.797; // rayon moyen de la Terre en km
    $dlat = $lat2 - $lat1;
    $dlng = $lng2 - $lng1;
    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin(
            $dlng / 2) * sin($dlng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $km = $r * $c;

    return ($miles ? ($km * 0.621371192) : $km);
}

?>
<div class="customContainer">
    <div class="leftColumn">
        <img class="logo" href="index.php" src="img/logo.png">
        <div class="contenuBarre">

            <center><strong><p><?php echo $htmlRechercherPar; ?></p></strong></center>
            <form method="get" action="index.php" class="p-3">
                <div class="mb-3">
                    <label for="categories" class="form-label"><?php echo $htmlParProfession ?></label>
                    <select name="categorie" id="categories" class="form-select">
                        <option value="Tout" <?php if ($_GET["categorie"] == "Tout") echo 'selected="selected"'; ?>><?php echo $htmlTout ?></option>
                        <option value="Agriculteur" <?php if ($_GET["categorie"] == "Agriculteur") echo 'selected="selected"'; ?>><?php echo $htmlAgriculteur ?></option>
                        <option value="Vigneron" <?php if ($_GET["categorie"] == "Vigneron") echo 'selected="selected"'; ?>><?php echo $htmlVigneron ?></option>
                        <option value="Maraîcher" <?php if ($_GET["categorie"] == "Maraîcher") echo 'selected="selected"'; ?>><?php echo $htmlMaraîcher ?></option>
                        <option value="Apiculteur" <?php if ($_GET["categorie"] == "Apiculteur") echo 'selected="selected"'; ?>><?php echo $htmlApiculteur ?></option>
                        <option value="Éleveur de volaille" <?php if ($_GET["categorie"] == "Éleveur de volaille") echo 'selected="selected"'; ?>><?php echo $htmlÉleveurdevolailles ?></option>
                        <option value="Viticulteur" <?php if ($_GET["categorie"] == "Viticulteur") echo 'selected="selected"'; ?>><?php echo $htmlViticulteur ?></option>
                        <option value="Pépiniériste" <?php if ($_GET["categorie"] == "Pépiniériste") echo 'selected="selected"'; ?>><?php echo $htmlPépiniériste ?></option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="rechercheVille" class="form-label"><?php echo $htmlParVille ?></label>
                    <input type="text" name="rechercheVille" id="rechercheVille" class="form-control" pattern="[A-Za-z0-9 ]{0,100}" value="<?php echo $rechercheVille ?>" placeholder="<?php echo $htmlVille; ?>">
                </div>
                <?php
                $queryAdrUti = $db->prepare(('SELECT Adr_Uti FROM UTILISATEUR WHERE Id_Uti= :utilisateur;'));
                $queryAdrUti->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
                $queryAdrUti->execute();
                $returnQueryAdrUti = $queryAdrUti->fetchAll(PDO::FETCH_ASSOC);

                if (count($returnQueryAdrUti) > 0) {
                    $Adr_Uti_En_Cours = $returnQueryAdrUti[0]["Adr_Uti"];
                    ?>
                    <div class="mb-3">
                        <label class="form-label"><?php echo $htmlAutourDeChezMoi . ' (' . $Adr_Uti_En_Cours . ')'; ?></label>
                        <input name="rayon" type="range" class="form-range" value="<?php echo $rayon; ?>" min="1" max="100" step="1" onchange="AfficheRange2(this.value)" onkeyup="AfficheRange2(this.value)">
                        <span id="monCurseurKm"><?php echo $htmlRayonDe ?><?php echo $rayon; if ($rayon >= 100) echo '+'; ?></span>
                        <script>
                            function AfficheRange2(newVal) {
                                var monCurseurKm = document.getElementById("monCurseurKm");
                                if ((newVal >= 100)) {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + "+ ";
                                } else {
                                    monCurseurKm.innerHTML = "Rayon de " + newVal + " ";
                                }
                            }
                        </script>
                        <?php echo $htmlKm ?>
                    </div>
                    <?php
                } else {
                    $Adr_Uti_En_Cours = 'France';
                }
                ?>
                <div class="mb-3">
                    <label for="tri" class="form-label"><?php echo $htmlTri ?></label>
                    <select name="tri" id="tri" class="form-select" required>
                        <option value="nombreDeProduits" <?php if ($tri == "nombreDeProduits") echo 'selected="selected"'; ?>><?php echo $htmlNombreDeProduits ?></option>
                        <option value="ordreNomAlphabétique" <?php if ($tri == "ordreNomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAl ?></option>
                        <option value="ordreNomAntiAlphabétique" <?php if ($tri == "ordreNomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParNomAntiAl ?></option>
                        <option value="ordrePrenomAlphabétique" <?php if ($tri == "ordrePrenomAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAl ?></option>
                        <option value="ordrePrenomAntiAlphabétique" <?php if ($tri == "ordrePrenomAntiAlphabétique") echo 'selected="selected"'; ?>><?php echo $htmlParPrenomAntiAl ?></option>
                    </select>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary"><?php echo $htmlRechercher ?></button>
                </div>
            </form>


        </div>
    </div>
    <div class="rightColumn">
        <div class="topBanner">
            <div class="divNavigation">
                <a class="bontonDeNavigation" href="index.php"><?php echo $htmlAccueil ?></a>
                <?php
                if (isset($_SESSION["Id_Uti"])) {
                    echo '<a class="bontonDeNavigation" href="messagerie.php">' . $htmlMessagerie . '</a>';
                    echo '<a class="bontonDeNavigation" href="achats.php">' . $htmlAchats . '</a>';
                }
                if (isset($_SESSION["isProd"]) and ($_SESSION["isProd"] == true)) {
                    echo '<a class="bontonDeNavigation" href="produits.php">' . $htmlProduits . '</a>';
                    echo '<a class="bontonDeNavigation" href="delivery.php">' . $htmlCommandes . '</a>';
                }
                if (isset($_SESSION["isAdmin"]) and ($_SESSION["isAdmin"] == true)) {
                    echo '<a class="bontonDeNavigation" href="panel_admin.php">' . $htmlPanelAdmin . '</a>';
                }
                ?>
            </div>

            <form action="language.php" method="post" id="languageForm">
                <select name="language" id="languagePicker" onchange="submitForm()">
                    <option value="fr" <?php if ($_SESSION["language"] == "fr") echo 'selected'; ?>>Français</option>
                    <option value="en" <?php if ($_SESSION["language"] == "en") echo 'selected'; ?>>English</option>
                    <option value="es" <?php if ($_SESSION["language"] == "es") echo 'selected'; ?>>Español</option>
                    <option value="al" <?php if ($_SESSION["language"] == "al") echo 'selected'; ?>>Deutsch</option>
                    <option value="ru" <?php if ($_SESSION["language"] == "ru") echo 'selected'; ?>>русский</option>
                    <option value="ch" <?php if ($_SESSION["language"] == "ch") echo 'selected'; ?>>中國人</option>
                </select>
            </form>
            <form method="post">

                <script>
                    function submitForm() {
                        document.getElementById("languageForm").submit();
                    }
                </script>
                <?php
                if (!isset($_SESSION)) {
                    session_start();
                }
                if (isset($_SESSION, $_SESSION['tempPopup'])) {
                    $_POST['popup'] = $_SESSION['tempPopup'];
                    unset($_SESSION['tempPopup']);
                }

                ?>

                <input type="submit" value="<?php if (!isset($_SESSION['Mail_Uti'])) {/*$_SESSION = array()*/;
                    echo($htmlSeConnecter);
                } else {
                    echo '' . $_SESSION['Mail_Uti'] . '';
                } ?>" class="boutonDeConnection">
                <input type="hidden" name="popup" value=<?php if (isset($_SESSION['Mail_Uti'])) {
                    echo '"info_perso"';
                } else {
                    echo '"sign_in"';
                } ?>>

            </form>

        </div>

        <h1> <?php echo $htmlProducteursEnMaj ?> </h1>
        <div class="gallery-customContainer">
            <?php
            // Replace this section - starting around line 368
            if ($_SERVER["REQUEST_METHOD"] == "GET") {
                if (isset($_GET["categorie"])) {
                    $categorie = htmlspecialchars($_GET["categorie"]);
                    try {
                        // Use the existing database connection from the top of the file
                        // $db was already established earlier

                        // Prepare the appropriate SQL query based on category
                        if ($_GET["categorie"] == "Tout") {
                            $requete = 'SELECT UTILISATEUR.Mail_Uti, UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, COUNT(PRODUIT.Id_Produit) as ProduitCount
                        FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
                        LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod=PRODUIT.Id_Prod
                        GROUP BY UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti
                        HAVING PRODUCTEUR.Prof_Prod LIKE :profession';

                            $stmt = $db->prepare($requete);
                            $profession = '%';
                            $stmt->bindParam(':profession', $profession);
                        } else {
                            $requete = 'SELECT UTILISATEUR.Mail_Uti, UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti, COUNT(PRODUIT.Id_Produit) as ProduitCount
                        FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti
                        LEFT JOIN PRODUIT ON PRODUCTEUR.Id_Prod=PRODUIT.Id_Prod
                        GROUP BY UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, 
                        UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti
                        HAVING PRODUCTEUR.Prof_Prod = :categorie';

                            $stmt = $db->prepare($requete);
                            $stmt->bindParam(':categorie', $categorie);
                        }

                        // Add city search condition if provided
                        if ($rechercheVille != "") {
                            $requete .= ' AND Adr_Uti LIKE :adresse';
                            $stmt = $db->prepare($requete);

                            // Rebind parameters as needed
                            if ($_GET["categorie"] == "Tout") {
                                $profession = '%';
                                $stmt->bindParam(':profession', $profession);
                            } else {
                                $stmt->bindParam(':categorie', $categorie);
                            }

                            $adressePattern = '%, _____ %' . $rechercheVille . '%';
                            $stmt->bindParam(':adresse', $adressePattern);
                        }
                        // Add sorting
                        $requete .= ' ORDER BY ';

                        if ($tri === "nombreDeProduits") {
                            $requete .= 'ProduitCount DESC';
                        } else if ($tri === "ordreNomAlphabétique") {
                            $requete .= 'Nom_Uti ASC';
                        } else if ($tri === "ordreNomAntiAlphabétique") {
                            $requete .= 'Nom_Uti DESC';
                        } else if ($tri === "ordrePrenomAlphabétique") {
                            $requete .= 'Prenom_Uti ASC';
                        } else if ($tri === "ordrePrenomAntiAlphabétique") {
                            $requete .= 'Prenom_Uti DESC';
                        } else {
                            $requete .= 'ProduitCount ASC';
                        }
                        // Prepare the statement with the complete query
                        $stmt = $db->prepare($requete);

                        // Rebind parameters again for the final query
                        if ($_GET["categorie"] == "Tout") {
                            $profession = '%';
                            $stmt->bindParam(':profession', $profession);
                        } else {
                            $stmt->bindParam(':categorie', $categorie);
                        }

                        if ($rechercheVille != "") {
                            $adressePattern = '%, _____ %' . $rechercheVille . '%';
                            $stmt->bindParam(':adresse', $adressePattern);
                        }
                        // Execute the query
                        $stmt->execute();
                        // Get coordinates of current user
                        $urlUti = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($Adr_Uti_En_Cours);
                        $coordonneesUti = latLongGps($urlUti);
                        $latitudeUti = $coordonneesUti[0];
                        $longitudeUti = $coordonneesUti[1];
//                         Fetch and display results
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        if (count($results) > 0) {
                            foreach ($results as $row) {
                                if ($row["Mail_Uti"]== "deleted" . $row["Id_Uti"]) {
                                    continue;
                                }
                                if ($rayon >= 100) {
                                    echo "<div class='card' style='width: 18rem;'>";
                                    echo '<img src="img_producteur/' . htmlspecialchars($row["Id_Prod"]) . '.png" class="card-img-top" alt="' . htmlspecialchars($htmlImageUtilisateur) . '">';
                                    echo '<div class="card-body">';
                                    echo '<a href="producteur.php?Id_Prod=' . htmlspecialchars($row["Id_Prod"]) . '" class="square1">';
                                    echo '<p class="card-text">';
                                    echo "Nom : " . htmlspecialchars($row["Nom_Uti"]) . "<br>";
                                    echo "Prénom : " . htmlspecialchars($row["Prenom_Uti"]) . "<br>";
                                    echo "Adresse : " . htmlspecialchars($row["Adr_Uti"]) . "<br>";
                                    echo '</p>';
                                    echo '</a>';
                                    echo '</div>';
                                    echo "</div>";
                                } else {
                                    $urlProd = 'https://nominatim.openstreetmap.org/search?format=json&q=' . urlencode($row["Adr_Uti"]);
                                    $coordonneesProd = latLongGps($urlProd);
                                    $latitudeProd = $coordonneesProd[0];
                                    $longitudeProd = $coordonneesProd[1];
                                    $distance = distance($latitudeUti, $longitudeUti, $latitudeProd, $longitudeProd);

                                    //
                                    if ($distance < $rayon) {
                                        echo "<div class='card' style='width: 18rem;'>";
                                            echo '<img src="img_producteur/' . htmlspecialchars($row["Id_Prod"]) . '.png" class="card-img-top" alt="Image utilisateur">';
                                            echo '<div class="card-body">';
                                                echo '<a href="producteur.php?Id_Prod=' . htmlspecialchars($row["Id_Prod"]) . '" class="square1">';
                                                echo '<p class="card-text">';
                                                    echo "Nom : " . htmlspecialchars($row["Nom_Uti"]) . "<br>";
                                                    echo "Prénom : " . htmlspecialchars($row["Prenom_Uti"]) . "<br>";
                                                    echo "Adresse : " . htmlspecialchars($row["Adr_Uti"]) . "<br>";
                                                echo '</p>';
                                                echo '</a>';
                                            echo '</div>';
                                        echo "</div>";
                                    }
                                }
                            }
                        } else {
                            echo $htmlAucunResultat;
                        }

                    } catch (PDOException $e) {
                        echo "Erreur de base de données : " . $e->getMessage();
                    }
                }
            }
            ?>
        </div>
        <br>
        <div class="basDePage">
            <form method="post">
                <input type="submit" value="<?php echo $htmlSignalerDys ?>" class="lienPopup">
                <input type="hidden" name="popup" value="contact_admin">
            </form>
            <form method="post">
                <input type="submit" value="<?php echo $htmlCGU ?>" class="lienPopup">
                <input type="hidden" name="popup" value="cgu">
            </form>
        </div>
    </div>
</div>
<?php require "popups/gestion_popups.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
