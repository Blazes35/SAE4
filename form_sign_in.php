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
            <h1>Partie gauche (4/5)</h1>
            <p>Ceci est la partie gauche de la page web.</p>
        </div>
        <div class="right-column">
            <div class="fixed-banner">
                <!-- Partie droite du bandeau -->
                <div class="banner-right">
					<a class="fixed-size-button" href="form_sign_up.php" >inscription</a>
                </div>
            </div>
			<div class="contenu">
				<form class="formulaire" action="traitement_formulaire_sign_in.php" method="post">
					<h1>Connection</h1>
					<?php
					if (isset($_GET['mail'])) {
 					  	 $mail = $_GET['mail'];
   						 echo " $mail <br>";
						}
					?>
					<label for="mail">mail :</label>
					<input type="text" pattern="[A-Za-z0-9._-]{1,20}@[A-Za-z0-9.-]{1,16}\.[A-Za-z]{1,4}" name="mail" id="mail" required><br><br>
					<?php 
						if (isset($_GET['pwd'])) {
 					  	 $pwd = $_GET['pwd'];
   						 echo " $pwd <br>";
						}?>
					<label for="pwd">password :</label>
					<input type="text" name="pwd" id="pwd" required><br><br>
					<input type="submit" value="Envoyer">
				</form>
				<label for="btn-producteur">Se connecté en tant que administrateur</label>
				<input type="button" onclick="window.location.href='form_sign_in_admin.php'" id="btn-admin" action="form_sign_up_amdin.php" value="administrateur">
				
				
				<form class="formulaire" action="bug_report.php" method="post">
						<p>report a bug</p>
						<label for="mail">mail :</label>
						<input type="text" name="mail" id="mail" required><br><br>
						<label for="message">message : </label>
						<input type="text" name="message" id="message" required><br><br>
						<input type="submit" value="Envoyer">
				</form>
				<a class="fixed-size-button" href="form_sign_up.php" >  vous n'avez pas encore de compte? </a>
			</div>
		</div>
    </div>
</body>
</html>