<?php
require_once 'db.php';
require_once 'check_auth.php';

$field = $_GET['field'] ?? '';
$query = $_GET['query'] ?? '';

if (empty($field) || empty($query)) {
    echo json_encode([]);
    exit;
}

// Определяем таблицу и поле в зависимости от запроса
$tableMap = [
    'oldTeacher' => ['table' => 'teachers', 'field' => 'lastname, name, surname'],
    'oldDiscipline' => ['table' => 'disciplines', 'field' => 'code, name'],
    'newTeacher' => ['table' => 'teachers', 'field' => 'lastname, name, surname'],
    'newDiscipline' => ['table' => 'disciplines', 'field' => 'code, name'],
    'group' => ['table' => "groups", 'field' => 'name'],
];

if (!isset($tableMap[$field])) {
    echo json_encode([]);
    exit;
}

$table = $tableMap[$field]['table'];
$fieldName = $tableMap[$field]['field'];

header('Content-Type: application/json');

// Поиск совпадений
if ($field === 'oldTeacher' || $field === 'newTeacher') {
    $results = $DB->getNameFields($table, $fieldName, $query);
}
elseif ($field === 'oldDiscipline' || $field === 'newDiscipline') {
    $results = $DB->getDisciplineFields($table, $fieldName, $query);
}
elseif ($field === 'group') {
    $results = $DB->getGroupFields($table, $fieldName, $query);
}


echo json_encode($results);