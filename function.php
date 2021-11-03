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

    if (password_verify($pass, $user["pass"])){
        $_SESSION["auth"] = true;
        $_SESSION["user"] =  [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"]
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

/**
 * @decription проверка авторизирован ли пользователь
 * @return bool
 */
function is_not_logged_in(): bool
{
    if (isset($_SESSION['auth']) && $_SESSION["auth"] === true){
        return false;
    }
    return true;
}

/**
 * @decription проверка админ ли
 * @return bool
 */
function is_admin(): bool
{
    if($_SESSION["user"]["role"] == "admin"){
        return true;
    }
    return false;
}

/**
 * @decription получить всех пользователей
 * @return array
 */
function get_all_users()
{
    $db = getConnection();
    $users = $db->query("SELECT * FROM users ")->fetchAll(PDO::FETCH_ASSOC);;

    return $users;
}

/**
 * @decription получить пользователя по id
 * @return array
 */
function get_user_by_id($id)
{
    if ($id){
        $db = getConnection();
        $sql = 'SELECT * FROM user_data WHERE user_id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}