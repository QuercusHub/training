<?php

/**
 * @param string - $email
 * поиск пользователя по  электронному адресу
 * @return array
 */
function get_user_by_email($email)
{

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

        set_name_message('message', 'Пользователь добавлен');
        redirect_to('public/page_register.php');

        return $id;





}

/**
 * @param string $name (ключ)
 * @param string $message (текст сообщения)
 * подготовить флеш сообщения
 * @return null
 */
function set_name_message($name, $message)
{
    $_SESSION[$name] = $message;

}

function redirect_to($path)
{
    header('Location: ' . $path);
}