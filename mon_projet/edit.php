<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mon_projet";

$connexion = new mysqli($servername, $username, $password, $database);

$id = "";
$name = "";
$email = "";
$phone = "";
$address = "";
$profilepicture = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET['id'])) {
        header("location: /mon_projet/index.php");
        exit;
    }

    $id = $_GET['id'];

    $sql = "SELECT * FROM clients WHERE id=$id";
    $result = $connexion->query($sql);
    $row = $result->fetch_assoc();

    if (!$row) {
        header("location: /mon_projet/index.php");
        exit;
    }

    $name = $row["name"];
    $email = $row["email"];
    $phone = $row["phone"];
    $address = $row["address"];
    $profilepicture = $row["profile_picture"];
} else {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = $_POST["password"];
    $profilepicture = $_POST["profile_picture_path_hidden"];

    $profilePicture = $_FILES['profile_picture'];

    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($profilePicture['name']);

        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $profilepicture = $uploadFile;
        } else {
            $errorMessage = "Erreur lors du téléchargement de la nouvelle photo.";
        }
    }

    do {
        if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($address)) {
            $errorMessage = "Tous les champs sont requis";
            break;
        }

        $sql = "UPDATE clients " .
            "SET name = '$name', email = '$email', phone = '$phone', address = '$address', profile_picture = '$profilepicture' ";

        if (!empty($password)) {
            $passwordhash = md5($password);
            $sql .= ", password = '$passwordhash' ";
        }

        $sql .= "WHERE id = $id";

        $result = $connexion->query($sql);

        if (!$result) {
            $errorMessage = "Requête invalide: " . $connexion->error;
            break;
        }

        $successMessage = "Client mis à jour correctement";

        header("location: /mon_projet/index.php");
        exit;
    } while (true);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Modifier Client</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
                <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                    <strong>$errorMessage</strong>
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
                ";
        }
        ?>
        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="profile_picture_path_hidden" value="<?php echo $profilepicture; ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nom</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Téléphone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Adresse</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address" value="<?php echo $address; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nouveau Mot de passe (optionnel)</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" value="">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Photo de profil actuelle</label>
                <div class="col-sm-6">
                    <?php if (!empty($profilepicture)) : ?>
                        <img src="<?php echo $profilepicture; ?>" alt="Photo de profil" style="max-width: 100px;">
                    <?php else : ?>
                        Aucune photo de profil
                    <?php endif; ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Nouvelle photo de profil</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="profile_picture">
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                    <div class='row mb-3'>
                        <div class='offset-sm-3 col sm-6'>
                            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                                <strong>$successMessage</strong>
                                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='close'></button>
                            </div>
                        </div>
                    </div>
                    ";
            }
            ?>
            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn  btn-outline-primary" href="/mon_projet/index.php" role="button">Annuler</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>