<?php
require "language.php";

session_start();

try {
    $pwd = $_POST['pwd'];
    $Mail_Uti = $_POST['mail'];

    if (!isset($_SESSION['test_pwd'])) {
        $_SESSION['test_pwd'] = 5;
    }

    if ($db) {
        $queryIdUti = $db->query('SELECT Id_Uti FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=\'' . $Mail_Uti . '\'');
        $returnQueryIdUti = $queryIdUti->fetchAll(PDO::FETCH_ASSOC);

        if ($returnQueryIdUti == NULL) {
            unset($Id_Uti);
            $_SESSION['erreur'] = $htmlAdresseMailInvalide;
            error_log("Adresse mail invalide: " . $Mail_Uti);
        } else {
            $Id_Uti = $returnQueryIdUti[0]["Id_Uti"];
            $query = $db->query('CALL verifMotDePasse(' . $Id_Uti . ', \'' . $pwd . '\')');
            $test = $query->fetchAll(PDO::FETCH_ASSOC);

            // Debugging: Check the result of the stored procedure
            error_log("Résultat de la procédure stockée verifMotDePasse: " . print_r($test, true));

            if (isset($_SESSION['test_pwd']) && $_SESSION['test_pwd'] > -10) {
                if ((isset($test[0][1]) and $test[0][1] == 1) or (isset($test[0][0]) and $test[0][0] == 1)) {
                    $_SESSION['Mail_Uti'] = $Mail_Uti;
                    $_SESSION['Id_Uti'] = $Id_Uti;

                    $bdd2 = dbConnect();
                    $isProducteur = $bdd2->query('CALL isProducteur('.$Id_Uti.');');
                    $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
                    $reponse = $returnIsProducteur[0]["result"];
                    $_SESSION["isProd"] = $reponse != NULL;

                    $_SESSION['erreur'] = '';
                    $bdd3 = dbConnect();
                    $isAdmin = $bdd3->query('SELECT Id_Uti FROM ADMINISTRATEUR WHERE Id_Uti='.$_SESSION["Id_Uti"]);
                    $returnIsAdmin = $isAdmin->fetchAll(PDO::FETCH_ASSOC);
                    $_SESSION["isAdmin"] = count($returnIsAdmin) > 0;
                    $_SESSION['erreur'] = '';
                    $_SESSION['message'] = "Connexion réussie !";
                    error_log("Connexion réussie pour l'utilisateur: " . $Mail_Uti);
                } else {
                    $_SESSION['test_pwd']--;
                    $_SESSION['erreur'] = $htmlMauvaisMdp . $_SESSION['test_pwd'] . $htmlTentatives;
                    $_SESSION['message'] = "Échec de la connexion.";
                    error_log("Échec de la connexion pour l'utilisateur: " . $Mail_Uti);
                }
            } else {
                $_SESSION['erreur'] = $htmlErreurMaxReponsesAtteintes;
                $_SESSION['message'] = "Échec de la connexion.";
                error_log("Échec de la connexion: nombre maximum de tentatives atteint pour l'utilisateur: " . $Mail_Uti);
            }
        }
    } else {
        $_SESSION['message'] = "Échec de la connexion à la base de données.";
        error_log("Échec de la connexion à la base de données.");
    }
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();
    error_log("Exception: " . $e->getMessage());
}
?>