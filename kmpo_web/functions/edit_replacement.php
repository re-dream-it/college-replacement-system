<?php

require_once 'db.php';
require_once 'check_auth.php';
require_once 'send_post.php';


$replace = $_POST ?? '';



$oldFilled = (!empty($replace['oldRoom']) && !empty($replace['oldTeacher']) && !empty($replace['oldPair']) && !empty($replace['oldDiscipline'])) ;
$oldEmpty = (empty($replace['oldRoom']) && empty($replace['oldTeacher']) && empty($replace['oldPair']) && empty($replace['oldDiscipline']));

if (!($oldFilled || $oldEmpty)){
    echo json_encode(['success' => false, 'message' => 'поля "Было" должны быть либо полностью пустыми, либо полностью заполненными!']);
    exit;
}

$newFilled = (!empty($replace['newRoom']) && !empty($replace['newTeacher']) && !empty($replace['newPair']) && !empty($replace['newDiscipline']));
$newEmpty = (empty($replace['newRoom']) && empty($replace['newTeacher']) && empty($replace['newPair']) && empty($replace['newDiscipline']));

if (!($newFilled || $newEmpty)){
    echo json_encode(['success' => false, 'message' => 'поля "Стало" должны быть либо полностью пустыми, либо полностью заполненными!']);
    exit;
}

$replacement = $DB->editReplacement($replace, $oldEmpty, $newEmpty, $replace['id']);
$replacement_id = $replacement['id'];


if ($replacement_id){
    if ($replacement['confirmed'] == 1){
        try {
            $url = "http://localhost:4310/edit_replace_notify";
            $data = ['replacement_id' => $replacement_id];
            $response = sendPostRequest($url, $data);
            echo json_encode(['success' => true, 'message' => $replacement_id]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Замена была изменена, однако не удалось отправить уведомления в Телеграм.']);
        }
    }
    else{
        echo json_encode(['success' => true, 'message' => $replacement_id]);
    }
}
else{
    echo json_encode(['success' => false, 'message' => "Ошибка изменения замены в БД!"]);
}
