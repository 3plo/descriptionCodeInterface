<?php
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 17:20
 */
namespace app\models\DataTranslators;


/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 17:11
 */
interface DataTranslatorInterface
{
    public function translateKey($dictionary, $arrayName = '');
}