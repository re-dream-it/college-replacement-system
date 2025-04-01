<?
require_once 'db.php';
require_once 'check_auth.php';
require_once 'send_post.php';


$input = json_decode(file_get_contents("php://input"), true);
$replacement_id = $input['replacement_id'] ?? '';



if ($replacement_id){
    $DB->confirmReplacement($replacement_id);

    try {
        $url = "http://localhost:305/replace_notify";
        $data = ['replacement_id' => $replacement_id];
        $response = sendPostRequest($url, $data);
        echo json_encode(['success' => true, 'message' => "Замена №$replacement_id была утверждена!"]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Замена была утверждена, однако не удалось отправить уведомления в Телеграм.']);
    }

}
else{
    echo json_encode(['success' => false, 'message' => 'Ошибка утверждения замены!']);
}


// В питоне допиши чтоб при удалении неподтв. не было уведов


