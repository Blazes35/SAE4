<?php
require_once "loadenv.php";
loadEnv();
$db = dbConnect();
// Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
$requete = 'SELECT * FROM UTILISATEUR WHERE UTILISATEUR.Mail_Uti=:mail';
$stmt = $db->prepare($requete);
$stmt->bindParam("mail",$_SESSION['Mail_Uti']); // "s" indique que la valeur est une chaîne de caractères
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
?>