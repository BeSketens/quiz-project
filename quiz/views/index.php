<?php

// quiz functions
require '../controller/quiz.func.php';
# db + functions
require '../model/db.access.php';
require '../model/db.func.php';
# user connect
require '../controller/connection.func.php';

# session
session_start();
# flags
$isConnected = isset($_SESSION['user_id']);
$isAdmin = isset($_SESSION['admin']);
# misc
$pageUrl = $_SERVER['PHP_SELF'];

# available to all
if (isset($_GET['id'])) {
    $id = htmlentities($_GET['id']);
    $quizzes = getChosenQuiz($id);

    if ($isConnected) {
        if (isset($_GET['rated']) && $_GET['rated'] == true) {
            $isRated = true;
        } else {
            $isRated = false;
        }
    }

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

$showQuizzes = true; # can only be false when user : login - logout - create acc

if ($isConnected) {
    if (isset($_GET['logout'])) {
        $logout = true;
        $showQuizzes = false;
    }
} else {
    if (isset($_GET['login'])) {
        $login = true;
        $showQuizzes = false;
    } elseif (isset($_GET['createAccount'])) {
        $createAcc = true;
        $showQuizzes = false;
    }
}

# login - create acc errors
if (isset($_GET['error'])) {
    $errorLabel = $_GET['error'];
    require '../controller/errors.php';
}
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
            <img src="./logo/quiz-logo.png" alt="logo">
            <a href="index.php">
                <h1>Titre du site</h1>
            </a>
            <nav>
                <?php if ($isAdmin) { ?>
                <a href="admin.php">Administration</a>
                <?php } 
                    if ($isConnected) { ?>
                    <a href="<?= $pageUrl . '?logout' ?>">Se déconnecter</a> 
                <?php } else { ?>
                    <a href="<?= $pageUrl . '?login' ?>">Se connecter</a>
                    <a href="<?= $pageUrl . '?createAccount' ?>">Devenir membre</a>
                <?php } ?>
            </nav>
        </div>
        <div id="right-header">
            <form action="<?= $pageUrl ?>" method="get">
                <label>
                    Filtre :
                    <input name="filter" type="text" placeholder="<?= $filter ?? 'Recherche' ?>">
                </label>
                <a href="index.php" style="color: darkmagenta">Effacer filtre</a>
            </form>
        </div>
    </header>
    <hr>
    <main>
        <?php
        if (!$showQuizzes) {
            if (isset($logout)) {
                logout($pageUrl);
            } else {
                if (isset($error)) echo $error;
                if (isset($login)){
                    displayForm();
                } elseif (isset($createAcc)) {
                    displayForm('create');
                }
            }    
        } else {
            if (!$isQuizNotAvailable) {
                foreach ($quizzes as $quiz) {
                    if (!isset($id)) {
                        # display summary of all quizzes or filtered quiz list
                        quizSummaryDisplayer($quiz);
                    } else {
                        # rate
                        rateQuiz($_SESSION['user_id'] ?? 0, $quiz['quiz_id'], $isRated);
                        # display result of quiz if submitted
                        if (isset($result)) echo $result;
                        # display quiz in form
                        quizDisplayer($quiz);
                    }
                }
            } else { ?>
                <h3 class="error" style="text-align: center;">Pas de résultat !</h3>
            <?php }
        }
        ?>
    </main>
    <hr>
    <footer>
        MON FOOTER, PAS LE TIEN &copy;
        <a href="#">Conditions d'utilisation</a>
    </footer>

</body>

</html>