<?php

namespace App\Service;

use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Contracts\Translation\TranslatorInterface;

class JsVars
{
    /**
     * @var array
     */
    private $variables = [];

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var array
     */
    private $translations;

    /**
     * @var array
     */
    private $routes;

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->variables[$key];
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return JsVars
     */
    public function __set($key, $value)
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->variables[$key]);
    }

    /**
     * @param string $key
     */
    public function __unset($key)
    {
        unset($this->variables[$key]);
    }

    /**
     * @return array
     */
    public function getVariables()
    {
        return $this->variables;
    }

    /**
     * @param TranslatorInterface $translator
     *
     * @return JsVars
     */
    public function enableTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->translations = [];

        return $this;
    }

    /**
     * @param string $key
     *
     * @return JsVars
     *
     * @throws \Exception
     */
    public function trans($key)
    {
        if (null === $this->translator) {
            throw new \Exception('Translator must be enabled to use trans()');
        }

        $this->translations[$key] = $this->translator->trans($key);

        return $this;
    }

    /**
     * @return array
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * @param Router $router
     *
     * @return JsVars
     */
    public function enableRouter(Router $router)
    {
        $this->router = $router;
        $this->routes = [];

        return $this;
    }

    /**
     * @param string $key
     *
     * @return JsVars
     *
     * @throws \Exception
     */
    public function addRoute($key, $params = [])
    {
        if (null === $this->router) {
            throw new \Exception('Router must be enabled to use addRoute()');
        }

        $this->routes[$key] = $this->router->generate($key, $params);

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
