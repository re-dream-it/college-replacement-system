<?php
require_once 'db.php';
require_once 'check_auth.php';


$type = $_GET['type'] ?? '';
$value = $_GET['value'] ?? '';
$date = $_GET['date'] ?? '';
$slot_id = $_GET['newPair'] ?? '';
$new_teacher = $_GET['newTeacher'] ?? '';
$group = $_GET['group'] ?? '';
$discipline = $_GET['discipline'] ?? '';

if (empty($type)) {
    echo json_encode(false);
    exit;
}

if($type === 'room'){
    $result = $DB->checkRoom($date, $value, $slot_id);
}
elseif($type === 'teacher'){
    $result = $DB->checkTeacher($date, $value, $slot_id);
}
elseif($type === 'discipline_relation'){
    $result = $DB->checkDisciplineRelation($group, $discipline);
}
else {
    throw new Exception("Недопустимый тип проверки");
}

echo json_encode($result);