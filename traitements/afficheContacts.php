<?php
require "./language.php";
require_once 'loadenv.php';
loadEnv();
$db = dbConnect();

function afficheContacts($id_user)
{
    require "./language.php";
    $db = dbConnect();
    $query = $db->query(('CALL listeContact(' . $id_user . ');'));
    $contacts = $query->fetchAll(PDO::FETCH_ASSOC);
    if (count($contacts) == 0) {
        $test = $htmlPasDeConversation;
        echo($test);
    } else {
        foreach ($contacts as $contact) {
            afficherContact($contact);
        }
    }
}

function afficherContact($contact)
{
    $str = $contact['Prenom_Uti'] . ' ' . $contact['Nom_Uti'];
    ?>
    <form method="get">
        <input type="submit" value="<?php echo($str); ?>">
        <input type="hidden" name="Id_Interlocuteur" value="<?php echo($contact['Id_Uti']) ?>">
    </form>
    <?php
}

if (isset($_SESSION['Id_Uti'])) {
    afficheContacts($_SESSION['Id_Uti']);
}
