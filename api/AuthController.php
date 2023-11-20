<?php
class AuthController
{
    static function registerAction($data)
    {
        if (empty($data)) {
            return ['res' => false, "message" => "Пустой запрос"];
        }

        $name = trim(strip_tags($data->name));
        $email = trim(strip_tags($data->email));
        $password = strip_tags($data->password);
        $confirmPassword = strip_tags($data->confirm_password);

        $user = new Users();
        if (empty(trim($name))) {
            return ['res' => false, "message" => "The name is required"];
        }
        if (empty(trim($email)) || !preg_match("/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/", $email)) {
            return ['res' => false, "message" => "Адрес электронной почты недействителен"];
        }
        if ($password != $confirmPassword) {
            return ['res' => false, "message" => "Пароли не совпадают"];
        }

        $user->name = $name;
        $user->email = $email;
        $user->password = md5($password);
        $user->confirmation_code = generateCode();
        $user->created_at = date('Y-m-d H:i:s');

        if ($user->checkEmail()) {
            return ['res' => false, 'message' => "Адрес электронной почты уже зарегистрирован"];
        }

        if ($user->save()) {
            if (sendConfirmationEmail($email, $user->confirmation_code)) {
                return ["res" => true];
            } else {
                return ["res" => false, "message" => "Произошла ошибка при отправке электронного письма"];
            }
        } else {
            return ["res" => false, "message" => "Произошла ошибка при создании вашей учетной записи"];
        }
    }

    static function confirmCodeAction($data){
        if(empty($data)){
            return ['res' => false, "message" => "Пустой запрос"] ;
        }
        $code = isset($data->code) ? $data->code : "";

        $user = new Users();
        $user->confirmation_code = strtoupper(trim(strip_tags($code)));

        $cuser = $user->getUserByCode();
        if (count($cuser) > 0) {
            $user->id = $cuser['id'];
            $user->created_at = $cuser['created_at'];
            if ($user->activateUser()) {
                return ['res' => true];
            } else {
                return ['res' => false, 'message' => "Срок действия вашего кода активации истек"] ;
            }
        }
        return ['res' => false, 'message' => "При попытке активировать учетную запись произошла ошибка"];
    }
    
    static function loginAction ($data){
        if(empty( $data)){
           return ['res' => false, "message" => "Пустой запрос"];
        }

        $user = new Users();
        if(isset($data->email) && isset($data->password)){
            $user->email = trim(strip_tags( $data->email));
            $user->password = md5( $data->password);

            $result = $user->validateCredentials();
            if($result !== false){
                $_SESSION['user'] = $result;
                die(json_encode(["res" => true]));
            }
        }
        return ['res' => false, 'message' => "Неверные учетные данные"];
    }
}
?>