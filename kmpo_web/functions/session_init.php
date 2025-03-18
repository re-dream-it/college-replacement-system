<?php
session_start();
require_once 'db.php';

// Обработка данных формы авторизации
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получение пользователя из БД
    $user = $DB->getEmployee($username);

    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'];
        header("Location: /index.php");
    } else {
        echo "Неверное имя пользователя или пароль.";
        header("Location: /login.php");
    }
}
?>