<?
require_once 'db.php';
require_once 'check_auth.php';
require_once 'send_post.php';


$input = json_decode(file_get_contents("php://input"), true);
$replacement_id = $input['replacement_id'] ?? '';



if ($replacement_id){
    $DB->introduceReplacement($replacement_id);
}
else{
    echo json_encode(['success' => false, 'message' => 'Ошибка при отметке замены!']);
}



