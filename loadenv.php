<?php
require 'vendor/autoload.php';
static $db =null;
function loadEnv(){
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
function dbConnect(){
    global $db;
    if ($db == null){
        $utilisateur = $_ENV['DB_USERNAME'];
        $serveur = $_ENV['DB_HOST'];
        $motdepasse = $_ENV['DB_PASSWORD'];
        $basededonnees = $_ENV['DB_DATABASE'];
        // Connect to database
        $db = new PDO('mysql:host=' . $serveur . ';dbname=' . $basededonnees, $utilisateur, $motdepasse);
    }
    return $db;
}

?>
