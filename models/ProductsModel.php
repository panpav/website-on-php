<?php

/**
 * Модель для таблицы продукции (products)
 * 
 */

/**
 * Получаем последние добавленные товары
 * 
 * @param integer $limit Лимит товаров
 * @return array Массив товаров
 */
function getLastProducts($limit = null){
    $sqli = "SELECT *
            FROM `products`
            ORDER BY id DESC";
    if($limit){
        $sqli .= " LIMIT {$limit}";
    }
    
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    
    return createSmartyRsArray($rs);
}

/**
 * Получить продукты для категории $itemId
 * 
 * @param integer $itemId ID категории
 * @return array массив продуктов
 */
function getProductsByCat($itemId){
    $itemId = intval($itemId);
    
    $sqli = "SELECT * 
            FROM `products`
            WHERE `category_id` = '{$itemId}'";
            
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    
    return createSmartyRsArray($rs);
}

/**
 * Получить данные продукта по ID
 * 
 * @param integer $itemId ID продукта
 * @return array массив данных продукта
 */
function getProductById($itemId){
    $itemId = intval($itemId);
    $sqli = "SELECT *
            FROM products
            WHERE id = '{$itemId}'";
    
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    return mysqli_fetch_assoc($rs);        
}

/**
 * Получить список продуктов из массива идентификаторов (ID's)
 * 
 * @param array $itemsIds массив идентификаторов продуктов
 * @return array массив данных продуктов
 */
function getProductsFromArray($itemsIds){
    $strIds = implode(', ', $itemsIds);
    
    $sqli = "SELECT *
            FROM `products`
            WHERE `id` in ({$strIds})";
    $rs = mysqli_query($GLOBALS["db"], $sqli);
    
    return createSmartyRsArray($rs);
}