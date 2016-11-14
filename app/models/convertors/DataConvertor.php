<?php
namespace app\models\convertors;
use app\models\DataTranslators\DataTranslatorInterface;
use app\models\dbConnector\DBConnectorInterface;

/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 19:07
 */
class DataConvertor
{
    /**
     * @var DBConnectorInterface
     */
    private $dbConnector;

    /**
     * @var DataTranslatorInterface
     */
    private $dataTranslator;

    /**
     * DataConvertor constructor.
     * @param DBConnectorInterface $dbConnector
     * @param DataTranslatorInterface $dataTranslator
     */
    public function __construct(DBConnectorInterface $dbConnector,DataTranslatorInterface $dataTranslator)
    {
        $this->dbConnector = $dbConnector;
        $this->dataTranslator = $dataTranslator;
    }

    public function convertData($tableName, $params, $takeAll = false)
    {
        $data = null;
        if($tableName == true)
        {

        }
        else
        {

        }
    }
}