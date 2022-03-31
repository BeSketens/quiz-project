<?php

try {
    $db = new PDO("mysql:host=localhost;dbname=dbquiz;charset=utf8", "root", "");
} catch (Exception $e) {
    die("Error : " . $e->getMessage());
}
