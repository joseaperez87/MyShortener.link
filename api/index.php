<?php
require_once '../Autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
        if (isset($_GET['a'])) {
            $jsonData = file_get_contents('php://input');
            $data = json_decode($jsonData);
            $action = $_GET['a'];
            $response = [];
            switch ($action){
                case 'savelink':
                    $response = LinksController::saveLinkAction($data);
                    break;
                case 'register':
                    $response = AuthController::registerAction($data);
                    break;
                case 'confirmcode':
                    $response = AuthController::confirmCodeAction($data);
                    break;
                case 'login':
                    $response = AuthController::loginAction($data);
                    break;
                default:
                    header('HTTP/1.1 404 Not Found');
                    echo json_encode(['message' => 'Ошибка: Запрошенное действие не найдено'], JSON_UNESCAPED_UNICODE);
                    break;
            }
            header('Content-Type: application/json');
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
        } else {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['message' => 'Ошибка: Запрос должен содержать действие'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['message' => 'Ошибка: Запрос должен содержать данные JSON'], JSON_UNESCAPED_UNICODE);
    }
} else {
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['message' => 'Ошибка: Разрешен только метод POST']);
}