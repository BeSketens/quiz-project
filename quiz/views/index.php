<?php

// list of quiz
#require '../controller/quizList.inc.php';
// quiz functions
require '../controller/quiz.func.php';
# db + functions
require '../model/db.access.php';
require '../model/db.func.php';

if (isset($_GET['id'])) {
    $id = htmlentities($_GET['id']);
    $quizzes = getChosenQuiz($id);

    if (isset($_POST['submit'])) {
        $result = calculReponsesCorrectes($quizzes);
    }
} elseif (isset($_GET['filter'])) {
    $filter = htmlentities($_GET['filter']);
    $quizzes = filterQuizzes($filter);
} else {
    $quizzes = getAllQuizzes();
}

$isQuizNotAvailable = empty($quizzes);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="design/style.css">
    <title>Accueil</title>
</head>

<body>
    <header id="page-header">
        <div id="left-header">
            <img src="./images/quiz-logo.png" alt="logo">
            <a href="index.php">
                <h1>Titre du site</h1>
            </a>
            <nav>
                <a href="admin.php">Administration</a>
            </nav>
        </div>
        <div id="right-header">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="get">
                <label>
                    Filtre :
                    <input name="filter" type="text" placeholder="<?= $filter ?? 'Recherche' ?>">
                </label>
                <a href="index.php" style="color: darkmagenta">Effacer filtre</a>
            </form>
        </div>
    </header>
    <hr>
    <main id="quiz-container">

        <?php
        if (!$isQuizNotAvailable) {
            foreach ($quizzes as $quiz) {
                if (!isset($id)) {
                    # display summary of all quizzes or filtered quiz list
                    quizSummaryDisplayer($quiz);
                } else {
                    # display result of quiz if submitted
                    if (isset($result)) echo $result;
                    # display quiz in form
                    quizDisplayer($quiz);
                }
            }
        } else { ?>
            <h3 class="error" style="text-align: center;">Pas de résultat !</h3>
        <?php } ?>

    </main>
    <hr>
    <footer>
        MON FOOTER, PAS LE TIEN &copy;
        <a href="#">Conditions d'utilisation</a>
    </footer>

</body>

</html>