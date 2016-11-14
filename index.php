<?php
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 10.11.2016
 * Time: 12:57
 */
session_start();
$user = 'root';
$pass = '';
$host = 'localhost';
$dbName = 'ekvitokdb';
$dbh = getDBConnection($host, $user, $pass, $dbName);
$content = getTranslateContent(__DIR__. './ru.json');

if(isset($_POST['code']) || isset($_POST['came_back']))
{
    if(isset($_POST['came_back']))
    {
        $cameBack = true;
    }
    if($_POST['code'] !== '')
    {
        $code = $_POST['code'];
        $_SESSION['code'] = $code;
        $discount = getDiscountInfo($code);
        #$status = chooseAction($code);
        $status = choosesAction($discount, $content);
        if($status !== false)
        {
            $active = $status['active'];
            $isUsed = $status['isUsed'];
        }
        $orders = getOrdersID($discount['ID']);
        if(checkDiscountCodeAmount($code))
        {
            $attachOrder = true;
        }
    }
    require_once(__DIR__ . './mainView.php');
}
else if(isset($_POST['Change']))
{
    if(isset($_SESSION['code']))
    {
        $active = isset($_POST['Active']) ? 1 : 0 ;
        $isUsed = isset($_POST['Is_Used']) ? 1 : 0;
        $isChanged = changeStatus($_SESSION['code'], $active, $isUsed);
    }
    require_once(__DIR__ . './mainView.php');
}
else if(isset($_POST['add']) || isset($_POST['attachOrder']))
{
    $code = $_SESSION['code'];
    if(isset($_POST['orderID']))
    {
        $orderID = $_POST['orderID'];
        if(isOrderExist($orderID))
        {
            if(checkCountTicketInOrder($orderID))
            {
                if (checkDiscountCodeAmount($code))
                {
                    $orderIDStatus = true;
                    if (checkOrder($orderID))
                    {
                        $isChanged = attachOrder($code, $orderID);
                    }
                    else
                    {
                        $errorMessage = "Не удалось обнаружить скидку с кодом или другая скидка уже задействована";
                    }
                }
                else
                {
                    $errorMessage = "Не возможно прикрепить код $code";
                }
            }
            else
            {
                $errorMessage = "В заказе $orderID нет билетов без скидки";
            }
        }
        else
        {
            $errorMessage = "Не обнаружено заказа $orderID";
        }
    }
    require_once(__DIR__ . './attachView.php');
}
else
{
    require_once(__DIR__ . './mainView.php');
}

function getDBConnection($host, $user, $pass, $dbName)
{
    $dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
    return $dbh;
}
//
function getOrdersInfo($discountID)
{
    if(isset($discountID))
    {
        global $dbh;
        $res = $dbh->query('SELECT DISTINCT t_order.* FROM t_ticket INNER JOIN t_order ON t_ticket.order_id = t_order.id WHERE t_ticket.used_discount_code_id = ' . $discountID);
        $orders = $res->fetchAll();
        foreach ($orders as $key => $value)
        {
            $orders[$key] = separateRow($value);
        }
        return $orders;
    }
    return null;
}

function getOrdersID($discountID)
{
    if(isset($discountID))
    {
        global $dbh;
        //$query = 'SELECT DISTINCT t_order.id FROM t_ticket INNER JOIN t_order ON t_ticket.order_id = t_order.id WHERE t_ticket.used_discount_code_id = ' . $discountID;
        //$query = "SELECT DISTINCT t_order.id FROM t_order WHERE id IN (SELECT DISTINCT t_ticket.order_id FROM t_ticket WHERE t_ticket.used_discount_code_id = $discountID)";
        $query = "SELECT DISTINCT t_ticket.order_id as id FROM t_ticket WHERE t_ticket.used_discount_code_id = $discountID";
        $res = $dbh->query($query);
        $orders = $res->fetchAll();
        foreach ($orders as $key => $value)
        {
            $orders[$key] = separateRow($value);
        }
        return $orders;
    }
    return null;
}

function getDiscountInfo($code)
{
    global $dbh;
    global $content;
    $res = $dbh->query("SELECT * FROM  t_discount_code WHERE t_discount_code.code = '" . $code . "'");
    $queryResult = $res->fetchAll();
    if(count($queryResult) !== 0)
    {
        $discountCodeInfo = $queryResult[0];
        $res = $dbh->query('SELECT * FROM  t_discount WHERE t_discount.id = ' . $discountCodeInfo['discount_id']);
        $discountInfo = $res->fetchAll()[0];
        $discountInfo = separateRow($discountInfo);
        $discountInfo = translateKey($discountInfo, $content, 'discount');
        $discountCodeInfo = separateRow($discountCodeInfo);
        $discountCodeInfo = translateKey($discountCodeInfo, $content, 'discount');
        $discount = array_merge($discountInfo, $discountCodeInfo);
        return $discount;
    }
    else
    {
        return false;
    }
}
//
function chooseAction($code)
{
    global $dbh;
    $res = $dbh->query('SELECT is_used, active FROM  t_discount_code WHERE t_discount_code.code = ' . $code . ' LIMIT 1;');
    if($res !== false)
    {
        $row = $res->fetchAll()[0];
        return ['active' => $row['active'], 'isUsed' => $row['is_used']];
    }
    else
    {
        return false;
    }
}

function choosesAction($discountInfo, $content)
{
    $resource = $content['discount'];
    if(count($discountInfo) > 0)
    {
        $result = ['active' => $discountInfo[$resource['active']] === 'ДА' ? 1 : 0, 'isUsed' => $discountInfo[$resource['is_used']] === 'ДА' ? 1 : 0];
        return $result;
    }
    else
    {
        return false;
    }
}

function changeStatus($code, $active, $isUsed)
{
    global $dbh;
    $query = "update t_discount_code set is_used = $isUsed, active = $active where code = $code";
    $countRow = $dbh->exec($query);
    return ($countRow > 0);
}

function separateRow($row)
{
    $result = [];
    if(isset($row['id']))
    {
        $result['id'] = $row[0];
    }
    foreach($row as $key => $value)
    {
        if(is_string($key))
        {
            if(strpos($key, 'id') === false && strpos($key, 'hash') === false)
            {
                if(strpos($key, 'type'))
                {
                    if($value == 0)
                    {
                        $result[$key] = 'Неизвестно';
                    }
                    elseif ($value == 1)
                    {
                        $result[$key] = 'Событийная скидка';
                    }
                    elseif ($value == 2)
                    {
                        $result[$key] = 'Глобальная';
                    }
                    continue;
                }
                if($value === '1' && strpos($key, 'multi_ticket') === false)
                {
                    $result[$key] = 'ДА';
                }
                elseif($value === '0')
                {
                    $result[$key] = 'НЕТ';
                }
                elseif ($value === '' || $value === null)
                {
                    $result[$key] = 'НЕТ ИНФОРМАЦИИ';
                }
                else
                {
                    $result[$key] = $value;
                }
            }
        }
    }
    return $result;
}

function getTranslateContent($storagePath)
{
    if(file_exists($storagePath))
    {
        $content = json_decode(file_get_contents($storagePath), true);
    }
    return $content;
}

function translateKey($dictionary, $content, $arrayName = '')
{
    $resource = $content[$arrayName];
    $result = [];
    foreach ($dictionary as $key => $value)
    {
        $traslateKeyName = $resource[$key];
        if($traslateKeyName !== null)
        {
            $result[$traslateKeyName] = $value;
        }
    }
    return $result;
}

function checkDiscountCodeAmount($code)
{
    global $dbh;
    $queryDiscountCode = "SELECT discount_id FROM t_discount_code WHERE code = $code;";
    $res = $dbh->query($queryDiscountCode);
    $resultList = $res->fetchAll();
    if($resultList !== false && count($resultList) > 0)
    {
        $id = $resultList[0]['discount_id'];
        $queryDiscount = "SELECT count(*) AS 'count' FROM t_discount WHERE id = $id AND amount = '0.00'";
        $res = $dbh->query($queryDiscount);
        $resultList = $res->fetchAll();
        if($resultList[0]['count'] > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    else
    {
        return false;
    }
}

function attachOrder($code, $orderID)
{
    global $dbh;

    $queryOrderUpdate = "UPDATE t_order SET discount = '0.00' WHERE id = $orderID;";
    $queryTicketsUpdate = "UPDATE t_ticket SET used_discount_code_id = (SELECT t_discount_code.id FROM t_discount_code WHERE t_discount_code.code = $code), discount = '0.00' WHERE order_id = $orderID";
    $orderUpdateStatus = $dbh->exec($queryOrderUpdate);
    if($orderUpdateStatus > 0)
    {
        $ticketsUpdateStatus = $dbh->exec($queryTicketsUpdate);
    }
    if($ticketsUpdateStatus > 0)
    {
        return true;
    }
    else
    {
        $queryOrderUpdate = "UPDATE t_order SET discount = NULL WHERE id = $orderID;";
        $dbh->exec($queryOrderUpdate);
        return false;
    }
}

function checkOrder($orderID)
{
    global $dbh;
    if($orderID === '')
    {
        return false;
    }
    $query = "SELECT count(*) AS 'count' FROM t_order WHERE id = $orderID AND discount IS NULL ;";
    $res = $dbh->query($query);
    $queryReslt = $res->fetchAll();
    if($queryReslt[0]['count'] != 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function checkCountTicketInOrder($orderID)
{
    global $dbh;
    $query = "SELECT count(*) AS 'count' FROM t_ticket WHERE order_id = $orderID GROUP BY order_id";
    $res = $dbh->query($query);
    $resultList = $res->fetchAll();
    if($resultList[0]['count'] > 0)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function isOrderExist($orderID)
{
    global $dbh;
    $query = "SELECT count(*) AS 'count' FROM t_order WHERE id = $orderID";
    $res = $dbh->query($query);
    return $res->fetchAll()[0]['count'] > 0;
}