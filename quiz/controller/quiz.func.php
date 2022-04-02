<?php

function quizSummaryDisplayer(array $quiz): void
{ ?>
    <a class="quiz-links" href="?id=<?= $quiz['quiz_id'] ?>">
        <article class="quiz-articles">
            <header>
                <h2><?= $quiz['quiz_titre'] ?></h2>
                <img src="<?= $quiz['illustration'] ?>" alt="" width="200">
            </header>
            <div>
                <h3><?= $quiz['description'] ?></h3>
                Nombre de questions : <?= sizeof($quiz['questions']) ?>
            </div>
        </article>
    </a>
<?php
}

function quizDisplayer(array $quiz): void
{ ?>
    <article class="quiz-articles">
        <div>
            <img src='<?= $quiz['illustration'] ?>' alt="" width="200">
            <h3>Quiz "<?= $quiz['quiz_titre'] ?>"</h3>
        </div>
        <div>
            <h4>Questions :</h4>
            <form action="<?= $_SERVER['PHP_SELF'] ?>?id=<?= $quiz['quiz_id'] ?>" method="post">
                <?php foreach ($quiz['questions'] as $qkey => $question) {
                    switch ($question['type']) {
                        case 'choix unique':
                            $type = 'radio';
                            break;
                        default:
                            $type = 'checkbox';
                            break;
                    }
                    $reponses = json_decode($question['reponses'], true);
                ?>
                    <h3><?= $question['question_titre'] ?></h3>

                    <?php foreach ($reponses as $rkey => $reponse) { ?>

                        <label>
                            <input name='question<?= $qkey ?><?php echo $type == 'checkbox' ? '[]' : '' ?>' type='<?= $type ?>' value='<?= $rkey ?>' />
                            <?= $reponse ?>
                        </label>
                        <br>
                    <?php }
                    ?>

                    <input type="hidden" value="<?= $question['bonneReponse'] ?>">
                    <br />
                <?php } ?>
                <button name="submit" type="submit">Submit</button>
            </form>
        </div>
    </article>
<?php
}

function calculReponsesCorrectes($quizzes)
{
    $answers = $_POST;
    array_pop($answers); # last value is the button

    $nbQuestions = 0;
    $cpt = 0;
    if (!empty($answers)) {
        foreach ($quizzes as $quiz) {
            $nbQuestions = sizeof($quiz['questions']);
            for ($i = 0; $i < sizeof($answers); $i++) {
                if ($quiz['questions'][$i]['bonneReponse'] == $answers['question' . $i]) $cpt++;
            }
        }

        if ($cpt == $nbQuestions) {
            $msg = 'Félicitation, score parfait !';
            $class = 'success';
        } elseif ($cpt > $nbQuestions / 2) {
            $msg = 'Bravo vous avez réussi avec ' . $cpt . ' bonnes réponses';
            $class = 'success';
        } else {
            $msg = 'Vous n\'avez eu que ' . $cpt . ' bonnes réponses sur ' . $nbQuestions . ' questions';
            $class = 'failure';
        }
    } else {
        $msg = 'Pas de points à calculer';
        $class = 'failure';
    }

    return '<h4 class="result ' . $class . '">' . $msg . '</h4>';
}

function rateQuiz(int $userId, int $quizId, bool $rateNotif)
{ 
    if ($userId != 0) { # == user not connected
        if (hasUserAlreadyRatedQuiz($userId, $quizId)) {
        ?>
            <form action="../controller/quizRate.script.php" method="POST">
                <input type="hidden" name="uid" value="<?= $userId ?>">
                <input type="hidden" name="qid" value="<?= $quizId ?>">
                <label>
                    <span id="rateValue">0</span>
                    <input id="rateInput" type="range" name="rate" min="0" max="5" value="0">
                </label>
                <button type="submit" name="rateSubmit">Évaluer le quiz</button>
            </form> 
            <script>
                let valueContainer = document.getElementById('rateValue');
                let valueInput = document.getElementById('rateInput');

                valueInput.addEventListener('change', () => {
                    valueContainer.innerHTML = valueInput.value;
                })
            </script>  
<?php
        } elseif ($rateNotif == true) { ?>
            <p class="success">Évaluation du quiz effetuée</p>
        <?php 
        } else { ?>
            <p class="rate">Note mise : <?= getQuizEvaluationOfUser($userId, $quizId) ?></p>
        <?php }
    }
}