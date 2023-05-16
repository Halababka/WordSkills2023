<?php

// echo json_encode(explode('/', key($_GET)));
require './lib.php';
$argc = explode('/', key($_GET));
$method = $argc[0];
$param = $argc[1];
$headers = getallheaders();

if (empty($method)) {
    echo 'Пустой запрос';
    exit;
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        if (
            $method == 'motherboards' || $method == 'processors' || $method == 'ram-memories' || $method == 'storage-devices' || $method == 'graphic-cards'
            || $method == 'power-supplies' || $method == 'machines' || $method == 'brands'
        ) {
            $pageSize = $_GET['pageSize'];
            $page = $_GET['page'];
            $token = explode(' ', $headers['Authorization']);
            $first_record_index = ($page - 1) * $pageSize;
            $rows_array = [];
            checkToken($token);
            if (!$pageSize) {
                $pageSize = 10;
            }
            $sql = "SELECT * FROM `motherboard` LIMIT $first_record_index, $pageSize";
            $result = $db->query($sql);
            if ($result) {
                while ($rows = mysqli_fetch_assoc($result)) {
                    array_push($rows_array, $rows);
                }
                echo json_encode(array(("response") => ["status code" => 200, "status text" => "OK", "body" => $rows_array]));
            } else {
                die("Ошибка MySQL: " . mysqli_error($db));
            }
        }
        break;
    case 'POST':
        switch ($method) {
            case 'register':
                $login = $_POST['login'];
                $password = $_POST['password'];
                if (empty($login) || empty($password)) {
                    echo json_encode(array(("response") => ["status code" => 403, "status text" => "Forbidden", "body" => ["message" => "Пустой логин или пароль"]]));
                    exit;
                }
                $sql = "INSERT INTO `users` (`login`, `password`) VALUES ('$login', '$password')";
                $result = $db->query($sql);
                if ($result) {
                    echo json_encode(array(("response") => ["status code" => 200, "status text" => "Created"]));
                } else {
                    echo json_encode(array(("resppnse") => ["status code" => 500, "status text" => "Server error", "body" => ["message" => "Запрос не отправился"]]));
                }
                break;
            case 'login':
                $login = $_POST['login'];
                $password = $_POST['password'];
                if (empty($login) || empty($password)) {
                    echo json_encode(array(("response") => ["Status code" => 403, "Status text" => "Forbidden", "body" => ["message" => "Пустой логин или пароль"]]));
                    exit;
                }
                $sql = "SELECT * FROM `users` WHERE `login`='$login'";
                $result = $db->query($sql);
                if ($result) {
                    if ($result->num_rows > 0) {
                        $rows = mysqli_fetch_array($result);
                        if ($rows['password'] === $password) {
                            if ($rows['token']) {
                                echo json_encode(array(("response") => ["Status code" => 403, "Status text" => "FORBIDDEN", "body" => ["message" => "Пользователь уже аутентифицирован"]]));
                                exit;
                            }
                            $token = md5(rand(0, 100));
                            $db->query("UPDATE `users` SET `token`='$token' WHERE `login`='$login' AND `password`='$password'");
                            echo json_encode(array(("response") => ["Status code" => 200, "Status text" => "OK", "body" => ["token" => $token]]));
                        } else {
                            echo json_encode(array(("response") => ["Status code" => 400, "Status text" => "BAD REQUEST", "body" => ["message" => "Неверные учетные данные"]]));
                        }
                    } else {
                        echo json_encode(array(("response") => ["Status code" => 404, "Status text" => "Not found"]));
                    }
                } else {
                    echo json_encode(array(("response") => ["status code" => 500, "status text" => "Server error", "body" => ["message" => "Запрос не отправился"]]));
                }
                break;
            case 'logout':
                $token = explode(' ', $headers['Authorization']);
                if (empty($token[1])) {
                    echo json_encode(array(("response") => ["status code" => 401, "status text" => "UNAUTHORIZED", "body" => ["message" => "Необходима аутентификация"]]));
                    exit;
                }
                $sql = "SELECT * FROM `users` WHERE `token`='$token[1]'";
                $result = $db->query($sql);
                if ($result) {
                    if ($result->num_rows > 0) {
                        $db->query("UPDATE `users` SET `token`='' WHERE `token`='$token[1]'");
                        echo json_encode(array(("response") => ["status code" => 200, "status text" => "OK", "body" => ["message" => "Успешный выход"]]));
                    } else {
                        echo json_encode(array(("response") => ["Status code" => 403, "Status text" => "FORBIDDEN", "body" => ["message" => "Неверный токен"]]));
                    }
                } else {
                    echo json_encode(array(("response") => ["status code" => 500, "status text" => "Server error", "body" => ["message" => "Запрос не отправился"]]));
                }
                break;
            case 'machines':
                $token = explode(' ', $headers['Authorization']);
                $motherboardId = $_POST['motherboardId'];
                $powerSupplyId = $_POST['powerSupplyId'];
                $processorId = $_POST['processorId'];
                $ramMemoryId = $_POST['ramMemoryId'];
                $ramMemoryAmount = $_POST['ramMemoryAmount'];
                $storageDevices = $_POST['storageDevices'];
                $graphicCardId = $_POST['graphicCardId'];
                $graphicCardAmount = $_POST['graphicCardAmount'];
                checkToken($token);
                break;
        }
        break;
}
