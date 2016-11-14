<?php
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 19:01
 */
namespace app\models\providers;


/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 18:56
 */
interface ViewProviderInterface
{
    /**
     * @param mixed $viewPath
     */
    public function setViewPath($viewPath);

    public function showView();
}