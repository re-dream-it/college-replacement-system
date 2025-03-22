<?php
/**
 * Функция для отправки POST-запроса на указанный URL.
 *
 * @param string $url URL, на который отправляется запрос.
 * @param array $data Данные, которые будут отправлены в теле запроса.
 * @return string Ответ от сервера.
 */
function sendPostRequest($url, $data) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception('Ошибка cURL: ' . curl_error($ch));
    }

    curl_close($ch);

    return $response;
}
