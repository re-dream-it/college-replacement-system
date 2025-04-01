<?php
require_once 'db.php';

$date = $_GET['date'] ?? '';
if (empty($date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Дата не указана']);
    exit;
}

// Запрос к базе данных
$replaces = $DB->getReplaces($date);

session_start();
if (!isset($_SESSION['user_id'])) {
    $replaces = $DB->getReplaces($date);
}
else{
    $replaces = $DB->getReplacesAdmin($date);
}

// Возвращаем данные в формате JSON
header('Content-Type: application/json');
echo json_encode($replaces);