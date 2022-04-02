<?php

# /!\
# Only getting quizzes with questions


# refactor data
function setUpDataArray($sqlResult)
{
    $quizzes = [];
    $quizIndex = 0;
    $questionIndex = 0;
    while ($data = $sqlResult->fetch()) {
        if (isset($quizzes[$quizIndex]['quiz_titre']) && $quizzes[$quizIndex]['quiz_titre'] != $data['quiz_titre']) {
            $quizIndex++;
            $questionIndex = 0;
        }

        $quizzes[$quizIndex]['quiz_id'] = $data['quiz_id'];
        $quizzes[$quizIndex]['quiz_titre'] = $data['quiz_titre'];
        $quizzes[$quizIndex]['description'] = $data['description'];
        $quizzes[$quizIndex]['illustration'] = $data['illustration'];

        $quizzes[$quizIndex]['questions'][$questionIndex]['question_titre'] = $data['question_titre'];
        $quizzes[$quizIndex]['questions'][$questionIndex]['type'] = $data['type'];
        $quizzes[$quizIndex]['questions'][$questionIndex]['reponses'] = $data['reponses'];
        $quizzes[$quizIndex]['questions'][$questionIndex]['bonneReponse'] = $data['bonneReponse'];

        $questionIndex++;
    }

    return $quizzes;
}

# select all
function getAllQuizzes()
{
    global $db;
    $request = $db->query('SELECT quiz.id AS quiz_id, quiz.titre AS quiz_titre, description, illustration, question.titre AS question_titre, type, reponses, bonneReponse
                            FROM quiz
                            JOIN quiz_question ON quiz_id = quiz.id
                            JOIN question ON question_id = question.id');

    return setUpDataArray($request);
}

# select specific quiz
function getChosenQuiz($id)
{
    global $db;
    $request = $db->prepare('SELECT quiz.id AS quiz_id, quiz.titre AS quiz_titre, description, illustration, question.titre AS question_titre, type, reponses, bonneReponse
                            FROM quiz
                            JOIN quiz_question ON quiz_id = quiz.id
                            JOIN question ON question_id = question.id
                            WHERE quiz.id = ?');
    $request->execute(array($id));

    return setUpDataArray($request);
}

# select through filter
function filterQuizzes(string $filter)
{
    $filterTmp = "%" . $filter . "%";
    global $db;
    $request = $db->prepare('SELECT quiz.id AS quiz_id, quiz.titre AS quiz_titre, description, illustration, question.titre AS question_titre, type, reponses, bonneReponse
                            FROM quiz
                            JOIN quiz_question ON quiz_id = quiz.id
                            JOIN question ON question_id = question.id
                            WHERE quiz.titre LIKE ?');
    $request->execute(array($filterTmp));

    return setUpDataArray($request);
}

# insert new user NOT ADMIN
function createUser(string $username, string $pwd, string $email, string $sexe)
{
    $type = 'membre';
    $activated = 'Y';
    $password = password_hash($pwd, CRYPT_BLOWFISH);

    global $db;
    $request = $db->prepare('INSERT INTO user (username, password, type, activated, email, sexe) VALUES (?, ?, ?, ?, ?, ?)');
    $request->execute(array($username, $password, $type, $activated, $email, $sexe));
}

#select user from db
function getUser(string $email, string $pwd)
{
    global $db;
    $request = $db->prepare('SELECT * FROM user WHERE email = ?');
    $request->execute(array($email));

    $user = $request->fetch();

    if (!empty($user)) {

        if (password_verify($pwd, $user['password'])) {
            # session var set up
            session_start();
            if ($user['type'] == 'admin'){
                $_SESSION['admin'] = true;
            }
            $_SESSION['user_id'] = $user['id'];
        } else {
            return false;
        }
    } else {
        return false;
    }

    return true;
}

# check if user already rated specific quiz
function hasUserAlreadyRatedQuiz(int $userId, int $quizId) : bool
{   
    global $db;
    $request = $db->prepare('SELECT id
                            FROM uq_evaluation
                            WHERE user_id = ? and quiz_id = ?');
    $request->execute(array($userId, $quizId));

    return empty($request->fetch());
}

# get quiz rate
function getQuizEvaluationOfUser(int $userId, int $quizId) : int
{
    global $db;
    $request = $db->prepare('SELECT evaluation
                            FROM uq_evaluation
                            WHERE user_id = ? and quiz_id = ?');
    $request->execute(array($userId, $quizId));
    $result = $request->fetch();

    return $result['evaluation'];
}

# rate a quiz
function sendQuizRate(int $userId, int $quizId, int $eval) : void
{
    global $db;
    $date = date('Y-m-d H:I:S');
    $request = $db->prepare('INSERT INTO uq_evaluation (user_id, quiz_id, evaluation, dateEvaluation) VALUES (?,?,?,?)');
    $request->execute(array($userId, $quizId, $eval, $date));
}
