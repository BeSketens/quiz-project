<?php

$login = isset($_POST['login']);
$create = isset($_POST['create']);

if ($login){
    $path = '../views/index.php?login';
}elseif($create){
    $path = '../views/index.php?createAccount';
}

require '../model/db.access.php';
require '../model/db.func.php';

if (!($login || $create)){
    header('Location: ../views/index.php');
    exit();
}   

$email = htmlspecialchars($_POST['email']); # common to both forms
$pwd = htmlspecialchars($_POST['password']); # common to both forms

if (strlen($email == 0) || strlen($pwd) == 0) { 
    header('Location: ' . $path . '&error=empty_fields');
    exit();
}

if ($login) {
    $auth = getUser($email, $pwd);

    if (!$auth) {
        header('Location: ' . $path . '&error=invalid_auth');
        exit();
    } 

    header('Location: ../views/index.php');
    exit();
    
} else {
    $username = htmlspecialchars($_POST['username']); # length
    $sexe = htmlspecialchars($_POST['sex']); # if no value
    
    if (strlen($username) > 60) {
        header('Location: ' . $path . '&error=username_length');
        exit();
    }

    if ($sexe != 'm' && $sexe != 'f') {
        header('Location: ' . $path . '&error=sex_error');
        exit();
    }

    createUser($username, $pwd, $email, $sexe);

    header('Location: ../views/index.php?login');
    exit();
}