<?php
ini_set('session.gc_maxlifetime', 43200); 
session_start([
    'cookie_lifetime' => 43200,
    'cookie_secure'   => true,    // Только HTTPS
    'cookie_httponly' => true,    // Запрет доступа из JavaScript
    'use_strict_mode' => true     // Защита от фиксации сессии
]);
require_once 'db.php';

// Обработка данных формы авторизации
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Получение пользователя из БД
    $user = $DB->getEmployee($username);

    // В session_init.php
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
    }

    if ($_SESSION['login_attempts'] >= 5) {
        die("Слишком много попыток. Попробуйте позже.");
    }

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['login_attempts']++;
    }

    if ($user && password_verify($password, $user['password'])) {
        // Успешная авторизация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['login'];
        $_SESSION['login_attempts'] = 0;
        header("Location: /index");
    } else {
        echo "Неверное имя пользователя или пароль.";
        header("Location: /login");
    }

}
?>