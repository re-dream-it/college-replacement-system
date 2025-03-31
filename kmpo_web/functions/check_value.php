<?php
require_once 'db.php';
require_once 'check_auth.php';

$table = $_GET['table'] ?? '';
$field = $_GET['field'] ?? '';
$value = $_GET['value'] ?? '';

if (empty($table) || empty($field) || empty($value)) {
    echo json_encode(false);
    exit;
}

if ($table === 'teachers') {
    $result = $DB->checkNameValue($table, $value);
}
elseif ($table === 'disciplines') {
    $result = $DB->checkDisciplineValue($table, $value);
}
elseif ($table === 'groups') {
    $result = $DB->checkGroupValue($table, $value);
}
elseif ($table === 'slots') {
    $result = $DB->checkSlotValue($table, $value);
}
elseif ($table === 'rooms') {
    $result = $DB->checkRoomExist($table, $value);
}
else {
    throw new Exception("Недопустимое имя таблицы");
}

$exists = $result > 0;
echo json_encode($exists);