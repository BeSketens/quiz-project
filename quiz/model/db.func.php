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
