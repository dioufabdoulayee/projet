<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "mon_projet";

$connexion = new mysqli($servername, $username, $password, $database);

$name = "";
$email = "";
$phone = "";
$address = "";
$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $password = $_POST["password"];

    // Gestion de l'upload de la photo de profil
    $profilePicture = $_FILES['profile_picture'];
    $profilepicture = null;

    if ($profilePicture['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($profilePicture['name']);

        if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
            $profilepicture = $uploadFile;
        } else {
            $errorMessage = "Erreur lors du téléchargement de la photo.";
        }
    }

    do {
        if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
            $errorMessage = "Tous les champs sont requis";
            break;
        }

        $passwordhash = md5($password);

        $sql = "INSERT INTO clients (name, email, phone, address, password, profile_picture)" .
            "VALUES ('$name', '$email', '$phone', '$address', '$passwordhash', '$profilepicture')";
        $result = $connexion->query($sql);

        if (!$result) {
            $errorMessage = "Requête invalide: " . $connexion->error;
            break;
        }

        $name = "";
        $email = "";
        $phone = "";
        $address = "";

        $successMessage = "Client ajouté correctement";

        header("location: /mon_projet/index.php");
        exit;
    } while (false);
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
        <h2>Nouveau Client</h2>

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
                <label class="col-sm-3 col-form-label">Mot de passe</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" name="password" value="">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Photo de profil</label>
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