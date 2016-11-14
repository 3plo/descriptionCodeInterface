<?php
namespace app\models\dbConnector;
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 10.11.2016
 * Time: 15:13
 */
class MySQLDBConnector implements DBConnectorInterface
{
    private $dbh;

    public function __construct($host, $dbName, $user, $pass)
    {
        try
        {
            $this->dbh = new \PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
        }
        catch(PDOException $exception)
        {
            throw new Exception('Connection exception');
        }
    }

    public function getAll($table,array $params = [])
    {
        $query = $this->generateSelectQuery($table, $params);
        $result = $this->exequteQuery($query, true);
        return $result;
    }

    public function getRow($table, array $params = [])
    {
        $query = $this->generateSelectQuery($table, $params, 1);
        $result = $this->exequteQuery($query, true);
        return $result[0];
    }

    public function add($table, array $params)
    {
        $query = $this->generateInsertQuery($table, $params);
        $this->exequteQuery($query);
    }

    public function change($table, array $params, array $attributes)
    {
        $query = $this->generateUpdateQuery($table, $params, $attributes);
        $this->exequteQuery($query);
    }

    public function delete($table, array $params = [])
    {
        $query = $this->generateDeleteQuery($table, $params);
        $this->exequteQuery($query);
    }

    private function generateSelectQuery($table, $params = [], $limit = 0)
    {
        $query = 'SELECT * FROM '.$table;
        if(count($params) > 0)
        {
            $query .= ' WHERE';
            foreach($params as $key => $value)
            {
                $query .= " ".$key." = '".$value."' AND";
            }
            $query = substr($query, 0, -3);
        }
        if($limit > 0)
        {
            $query .= " LIMIT $limit";
        }
        $query .= ';';
        return $query;
    }

    private function generateInsertQuery($table, $params)
    {
        $query = "INSERT INTO $table(";
        foreach ($params as $key => $value)
        {
            $query .= $key.', ';
        }
        $query = substr($query, 0, -2);
        $query .= ") VALUES(";
        foreach ($params as $key => $value)
        {
            $query .= $value.', ';
        }
        $query = substr($query, 0, -2);
        $query .= ");";
        return $query;
    }

    private function generateDeleteQuery($table, $params = [])
    {
        $query = "DELETE FROM $table";
        if(count($params) > 0)
        {
            $query .= ' WHERE';
            foreach($params as $key => $value)
            {
                $query .= " ".$key." = '".$value."' AND";
            }
            $query = substr($query, 0, -3);
        }
        $query .= ';';
        return $query;
    }

    private function generateUpdateQuery($table, array $params, array $attributes)
    {
        $query = "UPDATE $table SET ";
        foreach($attributes as $key => $value)
        {
            $query .= " ".$key." = '".$value."',";
        }
        $query = substr($query, 0, -1);
        $query .= ' WHERE ';
        foreach($params as $key => $value)
        {
            $query .= " ".$key." = '".$value."' AND";
        }
        $query = substr($query, 0, -3);
        $query .= ';';
        return $query;
    }

    private function clearResut($dirtyResult)
    {
        $clearResult = [];
        foreach($dirtyResult as $mediateValue)
        {
            $mediateResut = [];
            if(is_array($mediateValue))
            {
                foreach($mediateValue as $key => $value)
                {
                    if(is_string($key))
                    {
                        $mediateResut[$key] = $value;
                    }
                }
            }
            array_push($clearResult, $mediateResut);
        }
        return $clearResult;
    }

    private function exequteQuery($query, $selection = false)
    {
        $queryResult = $this->dbh->prepare($query);
        $queryResult->execute();
        $clearResult = [];
        if($selection === true)
        {
            $result = $queryResult->fetchAll();
            $clearResult = $this->clearResut($result);
        }
        return $clearResult;
    }

}