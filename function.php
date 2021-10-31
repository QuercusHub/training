<?php
require_once "db.php";
/**
 * @param string - $email
 * поиск пользователя по  электронному адресу
 * @return array
 */
function get_user_by_email($email)
{
    $db = getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);

    return $user = $stmt->fetch();
}

/**
 * @param string $email
 * @param string $pass
 * добавить пользователя в БД
 * @return int $id
 */
function add_user($email, $pass){

        $sql = "INSERT INTO users ( `email`, `pass`) VALUES ( '$email', '$pass')";
        $db = getConnection();
        $db->exec($sql);
        $id = $db->lastInsertId();

        set_flash_message('message', 'Регистрация успешна');
        redirect_to('page_login.php');

        return $id;

}

/**
 * @param string $email
 * @param string $pass
 * @decription авторизировать пользователя
 * @return bool
 */
function  login($email, $pass){
    $user = get_user_by_email($email);

    if (password_verify($pass, $user['pass'])){
        $_SESSION['user'] =  [
            'id' => $user['id'],
            'email' => $email
        ];
        redirect_to('users.php');
    }else {
        set_flash_message('message', 'Не верные данные для входа');
        redirect_to('page_login.php');
    }
    return true;
}

/**
 * @param string $name (ключ)
 * @param string $message (текст сообщения)
 * подготовить флеш сообщения
 * @return null
 */
function set_flash_message($name, $message)
{
    $_SESSION[$name] = $message;

}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit();
}

