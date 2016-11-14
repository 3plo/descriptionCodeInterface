<?php
namespace app\models\dbConnector;

/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 10.11.2016
 * Time: 15:09
 */
interface DBConnectorInterface
{

    public function getAll($table, array $params = []);

    public function getRow($table, array $params= []);

    public function add($table, array $values);

    public function change($table, array $values, array $params);

    public function delete($table, array $params = []);

}