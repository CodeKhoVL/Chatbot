<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/config.php');
function sanitizeInput($input)
{
    $input = strtolower($input);
    // Loại bỏ dấu chấm, dấu phẩy và khoảng trắng ở đầu câu
    $input = trim($input);
    return $input;
}

function chatbox($message)
{
    global $duogxaolin;
    /*
    $response = file_get_contents("https://script.google.com/macros/s/AKfycbw6Yz5IgSfL-DQyaDpg0fTh5H4Ic9vJNoJAEOQRz3oM9Azy2klD0F-_Ul28pAvMYUVIoQ/exec");
    $data = json_decode($response, true); */
    $cleanedMessage = sanitizeInput($message);
    $data = $duogxaolin->get_list("SELECT * FROM `data` ");
    foreach ($data as $item) {
        $keys = explode(',', $item["keyword"]);
        foreach ($keys as $key) {
            $cleanedKey = sanitizeInput($key);
            if (stripos($cleanedMessage, $cleanedKey) !== false) {
                return $item["content"];
            }
        }
    }
    return "Xin lỗi,Vui lòng nhập rõ câu hỏi";
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = $_POST["message"];
    $response = chatbox($userInput);
    echo $response;
}
