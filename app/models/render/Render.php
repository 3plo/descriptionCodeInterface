<?php
namespace app\models\Render;
use app\models\providers\ViewProviderInterface;

/**
 * Created by PhpStorm.
 * User: b.plotka
 * Date: 11.11.2016
 * Time: 18:45
 */
class Render implements RenderInterface
{
    /**
     * @var ViewProviderInterface
     */
    private $viewProvider;

    private $content;

    /**
     * Render constructor.
     * @param ViewProviderInterface $viewProvider
     */
    public function __construct(ViewProviderInterface $viewProvider)
    {
        $this->viewProvider = $viewProvider;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function show()
    {
        $this->viewProvider->showView();
    }

}