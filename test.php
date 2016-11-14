<?php
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 10.11.2016
 * Time: 15:44
 */

require_once (__DIR__ . './vendor/Autoloader.php');

$user = 'root';
$pass = '';
$host = 'localhost';
$dbName = 'ekvitokdb';
$dbConnector = new \app\models\dbConnector\MySQLDBConnector($host, $dbName, $user, $pass);
#echo var_dump($dbConnector);
$table = 't_discount_code';
//$dbConnector->getAll($table);
//echo '<br/>';

$params = ['code' => 666, 'discount_id' => 731];
$att = ['code' => 666];
$dbConnector->getAll($table, $params);

$dbConnector = null;
$dbConnector = new \app\models\dbConnector\MySQLDBConnector($host, $dbName, $user, $pass);
$table1 = 'country';
//$res = $dbConnector->getAll($table1);
//echo var_dump($res);
//$res1 = $dbConnector->getRow($table, $params);
//echo var_dump($res1);
//$dbConnector->add($table, $params);
//$dbConnector->delete($table, $params);
//$dbConnector->change($table, $params, $att);

echo var_dump(count(false));