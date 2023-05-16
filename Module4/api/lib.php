<?php

// mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli('localhost', 'root', '', 'am');
$db->set_charset('utf8');


function checkToken($token)
{
    global $db;
    if (empty($token[1])) {
        echo json_encode(array(("response") => ["status code" => 401, "status text" => "UNAUTHORIZED", "body" => ["message" => "Необходима аутентификация"]]));
        exit;
    }
    $sql = "SELECT * FROM `users` WHERE `token`='$token[1]'";
    $result = $db->query($sql);
    if ($result->num_rows <= 0) {
        echo json_encode(array(("response") => ["Status code" => 403, "Status text" => "FORBIDDEN", "body" => ["message" => "Неверный токен"]]));
        exit;
    }
}
