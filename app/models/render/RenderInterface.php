<?php
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 18:47
 */
namespace app\models\Render;


/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 18:45
 */
interface RenderInterface
{
    public function setContent($content);

    public function show();
}