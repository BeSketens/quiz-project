<?php

$folder = 'logo'; // dossier contenant l'image
$img = $_FILES["logo"];
$imgName = $_FILES["logo"]["name"];
$imgTmpName = $_FILES["logo"]["tmp_name"];
$imgError = $_FILES["logo"]["error"];
$imgSize = $_FILES["logo"]["size"];

if (isset($img) && $imgError == 0) { // upload de l'image

    $errorUploadProcess = 1;

    if ($imgSize <= 300000) {

        $infosImg = pathinfo($imgName);
        $extensionImg = $infosImg["extension"];
        $extensionsOk = array("png");

        if (in_array($extensionImg, $extensionsOk)) {
            $locationFile = $folder . '/quiz-logo.png';
            move_uploaded_file($imgTmpName, $locationFile);
            $errorUploadProcess = 0;
        } else {
            echo 'Seuls les PNG sont acceptés.'; // erreur mauvaise extension
            exit();
        }
    } else {
        echo 'Les fichiers sont limités à 300ko'; // erreur taille
        exit();
    }

} else if (isset($img) && $imgError != 0) { // erreur dès le début
    echo 'Impossible d\'envoyer le fichier';
    exit();
}