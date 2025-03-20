<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Document</title>
    <?php
//    require "language.php";
    require_once "loadenv.php"
    ?>
</head>
<body>
<?php
echo "<p>test</p>";
echo "1";
$db = dbConnect();
echo "2";
$utilisateur = htmlspecialchars($_SESSION["Id_Uti"]);
echo "3";
$filtreCategorie = 0;
echo "4";
if (isset($_POST["typeCategorie"]) == true) {
    echo "5";
    $filtreCategorie = htmlspecialchars($_POST["typeCategorie"]);
    echo "6";
}
?>
</body>
</html>