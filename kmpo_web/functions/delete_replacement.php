<?php
require_once 'db.php';
require_once 'check_auth.php';
require_once 'send_post.php';

header('Content-Type: application/json');

// Получение ID замены
$replacement_id = $_GET['replacement_id'];


if ($replacement_id){
    if($DB->isConfirmed($replacement_id) == 1){
        try {
            $url = "http://localhost:4310/replace_delete";
            $data = ['replacement_id' => $replacement_id];
            $response = sendPostRequest($url, $data);
    
            if (!$DB->deleteReplacement($replacement_id)) {
                echo json_encode(['success' => false, 'message' => 'Возникла ошибка при удалении замены из БД!']);
            }else{
                echo json_encode(['success' => true, 'message' => "Замена №$replacement_id была удалена!"]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }  
    }
    else{
        if (!$DB->deleteReplacement($replacement_id)) {
            echo json_encode(['success' => false, 'message' => 'Возникла ошибка при удалении замены из БД!']);
        }else{
            echo json_encode(['success' => true, 'message' => "Замена №$replacement_id была удалена!"]);
        }
    }
}
else{
    echo json_encode(['success' => false, 'message' => 'Не указан ID замены!']);
}
