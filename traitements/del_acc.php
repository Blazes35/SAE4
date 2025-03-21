<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once "../loadenv.php";
loadEnv();
$db = dbConnect();


if (isset($_POST["confirm_delete"]) && $_POST["confirm_delete"] == "oui") {
    ?>
    <script>
        alert("Le compte a bien été supprimé");
    </script>
    <?php
    if (isset($_POST["Id_Uti"])) {
        $utilisateur = htmlspecialchars($_POST["Id_Uti"]);// l'admin supprime
        $delParAdmin = true;
    } else {
        $utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
        $msg = "?msg=compte supprimer";
        $delParAdmin = false;
    }

    $isProducteur = $db->prepare('CALL isProducteur(:utilisateur)');
    $isProducteur->bindParam(':utilisateur', $utilisateur, PDO::PARAM_STR);
    $isProducteur->execute();
    $returnIsProducteur = $isProducteur->fetchAll(PDO::FETCH_ASSOC);
    $reponse = $returnIsProducteur[0]["result"];

    if ($reponse == NULL) {
        $db = dbConnect();
        $queryGetProduitCommande = $db->prepare('SELECT Id_Produit, Qte_Produit_Commande FROM produits_commandes WHERE Id_Uti = :utilisateur;');
        $queryGetProduitCommande->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $queryGetProduitCommande->execute();
        $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
        $iterateurProduit = 0;
        $nbProduit = count($returnQueryGetProduitCommande);
        while ($iterateurProduit < $nbProduit) {
            $Id_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
            $Qte_Produit_Commande = $returnQueryGetProduitCommande[$iterateurProduit]["Qte_Produit_Commande"];

            $updateProduit = "UPDATE PRODUIT SET Qte_Produit = Qte_Produit + :Qte_Produit_Commande WHERE Id_Produit = :Id_Produit";
            $bindUpdateProduit = $db->prepare($updateProduit);
            $bindUpdateProduit->bindParam(':Qte_Produit_Commande', $Qte_Produit_Commande, PDO::PARAM_INT);
            $bindUpdateProduit->bindParam(':Id_Produit', $Id_Produit, PDO::PARAM_INT);
            $bindUpdateProduit->execute();

            $iterateurProduit++;
        }

        $test = $db->prepare('UPDATE COMMANDE SET Id_Statut=3 WHERE Id_Uti= :utilisateur AND Id_Statut<>4;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();

        $test = $db->prepare('DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();


        $updateMailUtilisateur = $db->prepare(('UPDATE UTILISATEUR SET Mail_Uti="deleted'.$utilisateur.'" WHERE Id_Uti=:utilisateur;'));
        $updateMailUtilisateur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $updateMailUtilisateur->execute();

        $delUtilisateur = $db->prepare(('DELETE FROM UTILISATEUR WHERE Id_Uti=:utilisateur;'));
        $delUtilisateur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delUtilisateur->execute();
    } else {
        //echo ' producteur';
        $db = dbConnect();


        //id prod
        $queryIdProd = $db->prepare('SELECT Id_Prod FROM PRODUCTEUR WHERE Id_Uti=:Id_Uti;');
        $queryIdProd->bindParam(":Id_Uti", $utilisateur, PDO::PARAM_STR);
        $queryIdProd->execute();
        $returnQueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
        $IdProd = $returnQueryIdProd[0]["Id_Prod"];


        $queryGetProduitCommande = $db->prepare(('SELECT Id_Produit FROM PRODUIT WHERE Id_Prod = :IdProd;'));

        $queryGetProduitCommande->bindParam(":IdProd", $IdProd, PDO::PARAM_STR);
        $queryGetProduitCommande->execute();
        $returnQueryGetProduitCommande = $queryGetProduitCommande->fetchAll(PDO::FETCH_ASSOC);
        $iterateurProduit = 0;
        $nbProduit = count($returnQueryGetProduitCommande);
//        while ($iterateurProduit < $nbProduit) {
//            $Id_Produit = $returnQueryGetProduitCommande[$iterateurProduit]["Id_Produit"];
//
//
//            $delProduit = $db->prepare(('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;'));
//            $delProduit->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
//            $delProduit->execute();
//
//            $iterateurProduit++;
//        }
        $test = $db->prepare('UPDATE COMMANDE SET Id_Statut=3 WHERE Id_Uti= :utilisateur AND Id_Statut<>4;');
        $test->bindParam(':utilisateur', $utilisateur, PDO::PARAM_INT);
        $test->execute();

        $delMessage = $db->prepare(('DELETE FROM MESSAGE WHERE Emetteur= :utilisateur OR Destinataire= :utilisateur;'));
        $delMessage->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $delMessage->execute();

//        $delProducteur = $db->prepare(('DELETE FROM PRODUCTEUR WHERE Id_Uti=:utilisateur;'));
//        $delProducteur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
//        $delProducteur->execute();

        $updateMailUtilisateur = $db->prepare(('UPDATE UTILISATEUR SET Mail_Uti="deleted'.$utilisateur.'" WHERE Id_Uti=:utilisateur;'));
        $updateMailUtilisateur->bindParam(":utilisateur", $utilisateur, PDO::PARAM_STR);
        $updateMailUtilisateur->execute();

    }

    if ($delParAdmin == false) {
        header('Location: log_out.php' . $msg);
    } else {
        header('Location: ../panel_admin.php');
    }
//    header("Location: ../index.php");
} else {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/deleteAcc.css">
    </head>
    <body>
    <div class="customContainer">
        <form method="post" action="del_acc.php">
            <p>Voulez-vous vraiment supprimer votre compte</p>
            <input type="hidden" name="confirm_delete" value="oui">
            <button type="submit" class="delete-btn">Oui, supprimer mon compte</button>
            <?php if(isset($_POST["Id_Uti"])){
                echo '<input type="hidden" name="Id_Uti" value="' . htmlspecialchars($_POST["Id_Uti"]) . '">';
            }?>
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
