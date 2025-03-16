<?php
if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mon_projet";

    $connexion = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM  clients WHERE id=$id";
    $connexion->query($sql);
}

header("location: /mon_projet/index.php");
exit;
?>

