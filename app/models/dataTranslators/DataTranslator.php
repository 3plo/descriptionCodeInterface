<?php
namespace app\models\DataTranslators;
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 17:11
 */
class DataTranslator implements DataTranslatorInterface
{
    private $storage;

    public function __construct($storagePath)
    {
        if(file_exists($storagePath))
        {
            $this->storage = json_decode(file_get_contents($storagePath), true);
        }
    }

    public function translateKey($dictionary, $arrayName = '')
    {
        $resource = $this->storage[$arrayName];
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

}