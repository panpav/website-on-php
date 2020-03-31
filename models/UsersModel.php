<?php

/* 
 * Модель для таблицы пользователей (users)
 * 
 */

/**
 * Регистрация нового пользователя
 * 
 * @param string $email почта
 * @param string $pwdMD5 пароль зашифрованный в MD5
 * @param string $name имя пользователя
 * @param string $phone телефон 
 * @param string $address адрес пользователя
 * @return array массив данных нового пользователя
 */
function registerNewUser($email, $pwdMD5, $name, $phone, $address){
    $email = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $email));
    $name = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $name));
    $phone = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $phone));
    $address = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $address));
    
    $sqli = "INSERT INTO 
            `users` (`email`, `pwd`, `name`, `phone`, `address`)
            VALUES ('{$email}', '{$pwdMD5}', '{$name}', '{$phone}', '{$address}')";
    
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    
    if($rs){
        $sqli = "SELECT *
            FROM `users`
            WHERE (`email` = '{$email}' and `pwd` = '{$pwdMD5}')
            LIMIT 1";
        
        $rs = mysqli_query($GLOBALS["db"], $sqli);
        $rs = createSmartyRsArray($rs);
        
        if(isset($rs[0])){
            $rs['success'] = 1;
        } else {
            $rs['success'] = 0;
        }
    } else {
        $rs['success'] = 0;
    }
    
    return $rs;
}

/**
 * Проверка параметров для регистрации пользователя
 * 
 * @param string $email
 * @param string $pwd1 пароль
 * @param string $pwd2 повтор пароля
 * @return array результат
 */
function checkRegisterParams($email, $pwd1, $pwd2){
    $res = null;
    
    if( ! $email){
        $res['success'] = false;
        $res['message'] = 'Введите email';
    }
    
    if( ! $pwd1){
        $res['success'] = false;
        $res['message'] = 'Введите пароль';
    }
    
    if( ! $pwd2){
        $res['success'] = false;
        $res['message'] = 'Введите повтор пароля';
    }
    
    if($pwd1 != $pwd2){
        $res['success'] = false;
        $res['message'] = 'Пароли не совпадают';
    }
    
    return $res;
}

/**
 * Проверка почты (есть ли email адрес в БД)
 * 
 * @param string $email
 * @return array массив - строка из балицы users, либо пустой массив
 */
function checkUserEmail($email){
    $email = mysqli_real_escape_string($GLOBALS["db"], $email);
    $sqli = "SELECT `id`
            FROM `users`
            WHERE `email` = '{$email}'";
    
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    $rs = createSmartyRsArray($rs);
    
    return $rs;
}

/**
 * Аутентификация пользователя
 * 
 * @param string $email почта (логин)
 * @param string $pwd пароль
 * @return array массив данных пользователя
 */
function loginUser($email, $pwd){
    $email = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $email));
    
    if( ! $email || ! $pwd){
        $rs['success'] = false;
        $rs['message'] = 'Введите логин и пароль';
        return $rs;
    }
    
    $pwd = md5($pwd);
    
    $sqli = "SELECT * 
            FROM `users`
            WHERE (`email` = '{$email}' and `pwd` = '{$pwd}')
            LIMIT 1";
      
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    $rs = createSmartyRsArray($rs);
    
    if(isset($rs[0])){
        $rs['success'] = true;
    } else {
        $rs['success'] = false;
    }
    
    return $rs;
}

/**
 * Изменение данных пользователя
 * 
 * @param string $name имя пользователя
 * @param string $phone телефон
 * @param string $address адрес
 * @param string $pwd1 новый пароль
 * @param string $pwd2 повтор нового пароля
 * @param string $curPwd текущий пароль
 * @return boolean TRUE в случае успеха
 */
function updateUserData($name, $phone, $address, $pwd1, $pwd2, $curPwd){
    $email = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $_SESSION['user']['email']));
    $name = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $name));
    $phone = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $phone));
    $address = htmlspecialchars(mysqli_real_escape_string($GLOBALS["db"], $address));
    $pwd1 = trim($pwd1);
    $pwd2 = trim($pwd2);
    
    $newPwd = null;
    if ( $pwd1 && ($pwd1 == $pwd2)){
        $newPwd = md5($pwd1);
    }
    
    $sqli = "UPDATE `users` 
        SET ";
    
    if ($newPwd){
        $sqli .= "`pwd` = '{$newPwd}', ";
    }
    
    $sqli .= " `name` = '{$name}',
            `phone` = '{$phone}',
            `address` = '{$address}'
            WHERE 
            `email` = '{$email}' AND `pwd` = '{$curPwd}' 
            LIMIT 1";
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    
    return $rs;
}