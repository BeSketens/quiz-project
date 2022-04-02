<?php

if (isset($_POST['submit'])) {
    require '../controller/uploader.inc.php';
}
?>

<!DOCTYPE HTML>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="design/style.css">
    <title>Administration</title>
</head>

<body>
    <header id="page-header">
        <div id="left-header">
            <img src="./logo/quiz-logo.png" alt="logo">
            <a href="index.php">
                <h1>Titre du site</h1>
            </a>
            <nav>
                <a href="index.php">Accueil</a>
            </nav>
        </div>
    </header>
    <hr>
    <main>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
            <Label>Logo du site :</Label>
            <input type="file" name="logo" accept="image/png">
            <input type="submit" value="Envoyer" name="submit">
        </form>
    </main>

</body>

</html>