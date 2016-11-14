<?php
namespace app\models\providers;
/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 18:56
 */
class HTMLViewProvider implements ViewProviderInterface
{
    private $viewPath;

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    /**
     * @param mixed $viewPath
     */
    public function setViewPath($viewPath)
    {
        $this->viewPath = $viewPath;
    }

    public function showView()
    {
        require_once($this->viewPath);
    }
}