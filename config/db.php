<?php

/**
 * Инициализация подключения к БД
 * 
 */

$dblocation = "127.0.0.1";
$dbname = "myshop";
$dbuser = "root";
$dbpasswd = "";

// соединяемся с БД
// db сделана глобальной из-за использования mySqli вместо mySql
$GLOBALS["db"] = mysqli_connect($dblocation, $dbuser, $dbpasswd);

if(! $GLOBALS["db"]){
    echo 'Ошибка доступа к MySql';
    exit();
}

// Устанавливает кодировку по умолчанию для текущего соединения.
mysqli_set_charset($GLOBALS["db"], 'utf8');

if(! mysqli_select_db($GLOBALS["db"], $dbname) ){
    echo "Ошибка доступа к базе данных: {$dbname}";
    exit();
}