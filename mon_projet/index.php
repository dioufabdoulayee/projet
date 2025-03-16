<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>Liste des Clients</h2>
        <a class="btn btn-primary" href="/mon_projet/create.php" role="button">Nouveau Client</a>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Mot de passe</th> <th>Created At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "mon_projet";

                $connexion = new mysqli($servername, $username, $password, $database);

                if ($connexion->connect_error) {
                    die("connexion failed: " . $connexion->connect_error);
                }

                $sql = "SELECT * FROM clients";
                $result = $connexion->query($sql);

                if (!$result) {
                    die("Invalid query: " . $connexion->error);
                }

                while ($row = $result->fetch_assoc()) {
                    echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[name]</td>
                            <td>$row[email]</td>
                            <td>$row[phone]</td>
                            <td>$row[address]</td>
                            <td>$row[password]</td> <td>$row[created_at]</td>
                            <td>
                                <a class='btn btn-primary btn-sm' href='/mon_projet/edit.php?id=$row[id]'>Edit</a>
                                <a class='btn btn-danger btn-sm' href='/mon_projet/delete.php?id=$row[id]'>Delete</a>
                            </td>
                        </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>