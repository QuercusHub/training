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
function add_user($email, $pass)
{
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
function login($email, $pass)
{
    $user = get_user_by_email($email);

    if (password_verify($pass, $user["pass"])) {
        $_SESSION["auth"] = true;
        $_SESSION["user"] = [
            "id" => $user["id"],
            "email" => $user["email"],
            "role" => $user["role"]
        ];
        redirect_to('users.php');
    } else {
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
    if (isset($_SESSION['auth']) && $_SESSION["auth"] === true) {
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
    if ($_SESSION["user"]["role"] == "admin") {
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
    $users = $db->query("SELECT * FROM users INNER JOIN user_data
    ON users.id = user_data.user_id ")->fetchAll(PDO::FETCH_ASSOC);;

    return $users;
}

/**
 * @decription получить пользователя по id
 * @return array
 */
function get_user_by_id($id): array
{

    if ($id) {

        $db = getConnection();
        $sql = 'SELECT * FROM user_data WHERE user_id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
}

function update_user_profile($id, $name, $job, $phone, $adress)
{

    $db = getConnection();
    $sql = "UPDATE user_data SET name = :name, job = :job, phone = :phone, adress = :adress WHERE user_id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':name', $name, PDO::PARAM_STR);
    $result->bindParam(':job', $job, PDO::PARAM_STR);
    $result->bindParam(':phone', $phone, PDO::PARAM_STR);
    $result->bindParam(':adress', $adress, PDO::PARAM_STR);
    $result->execute();
    set_flash_message("edit", "Профиль успешно обновлен");
    redirect_to("users.php");
}

function update_security_profile($id, $email, $pass)
{

    $db = getConnection();

    $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);
    $sql = "UPDATE `users` SET email = :email, pass = :pass WHERE id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':email', $email, PDO::PARAM_STR);
    $result->bindParam(':pass', $pass, PDO::PARAM_STR);
    $result->execute();
    set_flash_message("edit", "Настройки безопасности успешно обновлены");
    redirect_to("users.php");
}

function create_user($name, $job, $phone, $adress, $email, $pass, $status, $path, $vk, $telegram, $instagram)
{
    $db = getConnection();

    $sql = "INSERT INTO `users` SET email = :email, pass = :pass ";

    $result = $db->prepare($sql);
    $result->bindParam(':email', $email, PDO::PARAM_STR);
    $result->bindParam(':pass', $pass, PDO::PARAM_STR);

    if ($result->execute()) {

        $last_id = $db->lastInsertId();
        $sql2 = "INSERT INTO `user_data` SET user_id = :user_id, name = :name,  job = :job, phone = :phone, adress = :adress, status = :status, avatar = :avatar, vk = :vk, telegram = :telegram, instagram = :instagram";
        $result2 = $db->prepare($sql2);
        $result2->bindParam(':user_id', $last_id);
        $result2->bindParam(':name', $name);
        $result2->bindParam(':job', $job);
        $result2->bindParam(':phone', $phone);
        $result2->bindParam(':adress', $adress);
        $result2->bindParam(':status', $status);
        $result2->bindParam(':avatar', $path);
        $result2->bindParam(':vk', $vk);
        $result2->bindParam(':telegram', $telegram);
        $result2->bindParam(':instagram', $instagram);
        $result2->execute();
    }
    set_flash_message("edit", "Новый пользователь добавлен!");
    redirect_to("users.php");
}

function get_status_user($status)
{
    if ($status == "online") {
        echo "success";
    } elseif ($status == "away") {
        echo "warning";
    } else {
        echo "danger";
    }
}

function set_status($id, $status)
{
    $db = getConnection();

    $sql = "UPDATE `user_data` SET status = :status WHERE user_id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':status', $status, PDO::PARAM_STR);
    $result->execute();

    set_flash_message("edit", "Статус обновлен!");
    redirect_to("users.php");
}

function save_path_avatar($id, $avatar)
{
    $db = getConnection();

    $sql = "UPDATE `user_data` SET avatar = :avatar WHERE user_id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->bindParam(':avatar', $avatar, PDO::PARAM_STR);
    $result->execute();

    set_flash_message("edit", "Аватар установлен");
    redirect_to("users.php");
}

function delete_user($id)
{
    $db = getConnection();

    $sql = "DELETE user_data, `users` FROM user_data
	LEFT JOIN `users` ON user_data.user_id = users.id WHERE user_data.user_id = :id";
    $result = $db->prepare($sql);
    $result->bindParam(':id', $id, PDO::PARAM_INT);
    $result->execute();

    set_flash_message("edit", "Пользователь удален");
    redirect_to("users.php");
}