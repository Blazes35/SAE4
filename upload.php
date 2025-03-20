<?php
require_once 'loadenv.php';
loadEnv();
$db=dbConnect();

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si le fichier a été correctement téléchargé
    if (isset($_FILES["image"])) {
        session_start();
        // Spécifier le chemin du dossier de destination
        $targetDir = __DIR__ . "/img_producteur/";

        if (isset($_SESSION["Mail_Uti"])) {
            $mailUti = $_SESSION["Mail_Uti"];
        } else {
            $mailUti = $_SESSION["Mail_Temp"];
        }
        $requete = 'SELECT PRODUCTEUR.Id_Prod FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE UTILISATEUR.Mail_Uti = :mail';
        $queryIdProd = $db->prepare($requete);
        $queryIdProd->bindParam(':mail', $mailUti, PDO::PARAM_STR);
        $queryIdProd->execute();
        $returnqueryIdProd = $queryIdProd->fetchAll(PDO::FETCH_ASSOC);
        $Id_Prod=$returnqueryIdProd[0]["Id_Prod"];

        // Obtenir l'extension du fichie
        $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        // Utiliser l'extension dans le nouveau nom du fichier
        $newFileName = $Id_Prod . '.' . $extension;

        // Créer le chemin complet du fichier de destination
        $targetPath = $targetDir . $newFileName;
        
        unlink( $targetPath ); 
        // Déplacer le fichier téléchargé vers le dossier de destination
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetPath)) {
            echo "<br>L'image a été téléchargée avec succès. Nouveau nom du fichier : $newFileName<br>";
            
        header('Location: ./index.php');  
        } else {
            echo "Le déplacement du fichier a échoué. Erreur : " . error_get_last()['message'] . "<br>";
        }
    } else {
        echo "Veuillez sélectionner une image.<br>";
    }
}

?>