<!DOCTYPE html>
<html>
<head>

<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
    <div class="container">
        <div class="left-column">
            <img class="logo" src="img/logo.png">
			 <p>recherche par catégorie</p>

                            
			<form method="get" action="index.php"> 

			<label for="categories">Sélectionnez une catégorie :</label>
			<select name="categorie" id="categories">
                <option value="Tout">Tout</option>
				<option value="Agriculteur">Agriculteur</option>
				<option value="Vigneron">Vigneron</option>
				<option value="Maraîcher">Maraîcher</option>
				<option value="Apiculteur">Apiculteur</option>
				<option value="Éleveur de volaille">Éleveur de volaille</option>
				<option value="Viticulteur">Viticulteur</option>
				<option value="Pépiniériste">Pépiniériste</option>
			</select>
			<input type="submit" value="Aller à la catégorie">
			</form>
			
        </div>
        <div class="right-column">
            <div class="fixed-banner">
                <!-- Partie gauche du bandeau -->
                <div class="banner-left">
                    <div class="button-container">
                        <button class="button"><a href="index.php">accueil</a></button>
                        <button class="button"><a href="message.php">messagerie</a></button>                 
						<button class="button"><a href="commandes.php">commandes</a></button>

                    </div>
                </div>
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					
					<?php 
                    
                    session_start();
                    if (isset($_SESSION['Mail_Uti'])) {  
                    echo '<a class="fixed-size-button" href="user_informations.php" >';
					echo $_SESSION['Mail_Uti']; 
					}
					else {
                    echo '<a class="fixed-size-button" href="form_sign_in.php" >';
					echo "connection";
					}
					
					?>
					
					
					</a>    
                </div>
            </div>
			<div class="contenu">
            <!-- Contenu de la partie droite (sous le bandeau) -->
            <h1> PRODUCTEURS : </h1>
            <?php
            
            echo('la tu vois si il est producteur');
            var_dump($_SESSION["isProd"]);
            echo("php version debug<br>");
            echo phpversion();
            echo '<h1>'.$_SESSION['Mail_Uti'].'</h1>';
             ?> 
				<div class="gallery-container">
                        <?php
                        if ($_SERVER["REQUEST_METHOD"] == "GET") {
                            if (isset($_GET["categorie"])) {
                                $categorie = $_GET["categorie"];
                                // Connexion à la base de données 
                                $utilisateur = "inf2pj02";
                                $serveur = "localhost";
                                $motdepasse = "ahV4saerae";
                                $basededonnees = "inf2pj_02";
                                $connexion = new mysqli($serveur, $utilisateur, $motdepasse, $basededonnees);
                                // Vérifiez la connexion
                                if ($connexion->connect_error) {
                                    die("Erreur de connexion : " . $connexion->connect_error);
                                }

                                // Préparez la requête SQL en utilisant des requêtes préparées pour des raisons de sécurité
                                if ($_GET["categorie"]=="Tout"){
                                    $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti';
                                }else{
                                    $requete = 'SELECT UTILISATEUR.Id_Uti, PRODUCTEUR.Prof_Prod, PRODUCTEUR.Id_Prod, UTILISATEUR.Prenom_Uti, UTILISATEUR.Nom_Uti, UTILISATEUR.Adr_Uti FROM PRODUCTEUR JOIN UTILISATEUR ON PRODUCTEUR.Id_Uti = UTILISATEUR.Id_Uti WHERE PRODUCTEUR.Prof_Prod ="'.$categorie.'"';
                                    //$stmt->bind_param("s", $categorie);
                                }
                                $stmt = $connexion->prepare($requete);
                                 // "s" indique que la valeur est une chaîne de caractères
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<a href="producteur.php?Id_Prod='. $row["Id_Uti"] . '" class="square"  >';
                                        echo "Nom : " . $row["Nom_Uti"] . "<br>";
                                        echo "Prénom : " . $row["Prenom_Uti"] . "<br>";
                                        echo "Adresse : " . $row["Adr_Uti"] . "<br>";
                                        echo '<img src="/~inf2pj02/img_producteur/' . $row["Id_Prod"]  . '.png" alt="Image utilisateur" style="width: 100%; height: 85%;" ><br>';
                                        echo '</a> ';                                        
                                    }
                                } else {
                                    echo "Aucun résultat trouvé pour la catégorie : $categorie";
                                }
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {

                                        
                                    }
                                }
                                $stmt->close();
                                $connexion->close();
                            }
                        }
                        var_dump($_SESSION["Id_Uti"]);
                        echo("<br>");
                        var_dump($_SESSION["Mail_Uti"]);
                        ?>
                    
                </div>
					
				
			</div>
			<form class="formulaire" action="bug_report.php" method="post">
					<p class= "centered">report a bug</p>
					<label for="mail">mail :</label>
					<input type="text" name="mail" id="mail" required><br><br>
					<label for="pwd">message : </label>
					<input type="text" name="message" id="message" required><br><br>
					<input type="submit" value="Envoyer">
			</form>
			
		</div>
    </div>
</body>
</html>
