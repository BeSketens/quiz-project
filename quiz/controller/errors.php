<?php
$errorPre = '<p class="error" style="text-align:center">';
$error = '';
$errorSub = '</p>';
switch($errorLabel){
    case 'empty_fields':
        $error = 'Remplissez tous les champs';
        break;
    case 'username_length':
        $error = 'Le nom d\'utilisateur ne peut dépasser 60 charactères';
        break;
    case 'password_length':
        $error = 'Le mot de passe doit être entre X et Y charactères';
        break;
    case 'sex_error' :
        $error = 'Sexe invalide';
        break;   
    case 'invalid_auth':
        $error = 'Email ou mot de passe invalide';
        break;     
    default:
        $error = 'Une erreur est survenue';    
}

$error = $errorPre . $error . $errorSub;