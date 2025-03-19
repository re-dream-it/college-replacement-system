<?php
require_once 'db.php';


$type = $_GET['type'] ?? '';
$value = $_GET['value'] ?? '';
$date = $_GET['date'] ?? '';
$slot_id = $_GET['newPair'] ?? '';
$new_teacher = $_GET['newTeacher'] ?? '';

if (empty($type) || empty($value)) {
    echo json_encode(false);
    exit;
}

if($type === 'room'){
    $result = $DB->checkRoom($date, $value, $slot_id);
}
elseif($type === 'teacher'){
    $result = $DB->checkTeacher($date, $value, $slot_id);
}
else {
    throw new Exception("Недопустимый тип проверки");
}

echo json_encode($result);