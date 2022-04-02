<?php

if (!isset($_POST['rateSubmit'])) {
    header('Location: ../views/index.php');
    exit();
}

$rate = htmlspecialchars($_POST['rate']);
$userId = htmlspecialchars($_POST['uid']);
$quizId = htmlspecialchars($_POST['qid']);

# pas de verif sur la validité du user_id et quiz_id

if (!is_numeric($rate) || ($rate < 0 && $rate > 5 )) {
    header('Location: ../views/index.php');
    exit();
}

require '../model/db.access.php';
require '../model/db.func.php';

sendQuizRate($userId, $quizId, $rate);

header('Location: ../views/index.php?id=' . $quizId . '&rated=true');
exit();