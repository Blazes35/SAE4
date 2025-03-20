<?php
require_once "../loadenv.php";
loadEnv();
$db=dbConnect();

      $Id_Produit=htmlspecialchars($_POST["deleteIdProduct"]);

      $delContenu=$db->prepare('DELETE FROM CONTENU WHERE Id_Produit=:Id_Produit;');
      $delContenu->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
      $delContenu->execute();


      $delProduct=$db->prepare('DELETE FROM PRODUIT WHERE Id_Produit=:Id_Produit;');
      $delProduct->bindParam(":Id_Produit", $Id_Produit, PDO::PARAM_STR);
      $delProduct->execute();

      // suppression de l'image (path à changer sur le serveur !!!!)
      $imgpath = "img_produit/".$Id_Produit.".png";
      //echo $imgpath;
      unlink( $imgpath ); 
    header('Location: produits.php');
?>