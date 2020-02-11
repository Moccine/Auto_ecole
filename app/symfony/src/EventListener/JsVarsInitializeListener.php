<?php

namespace App\EventListener;

use App\Service\JsVars;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class JsVarsInitializeListener
{
    /**
     * @var JsVars
     */
    private $jsVars;

    /**
     * @var bool
     */
    private $appDebug;

    /**
     * @param JsVars $jsVars
     * @param bool   $appDebug
     */
    public function __construct(JsVars $jsVars, $appDebug)
    {
        $this->jsVars = $jsVars;
        $this->appDebug = $appDebug;
    }

    /**
     * Initialize js vars.
     *
     * @param FilterControllerEvent $event
     *
     * @throws \Exception
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        // JsVars service will only initialize for HTML request
        if ($event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        // Simple variables
        $this->jsVars->debug = $this->appDebug;

        // Translations
        $this->jsVars->trans('form.field.error');
        $this->jsVars->trans('app.action.confirm');
        $this->jsVars->trans('app.action.cancel');
        $this->jsVars->trans('app.action.close');
        //package
        $this->jsVars->trans('package.add.error');

        // Routes
        $this->jsVars->addRoute('add_course', ['instructor_id' => '__instructor_id__']);
        $this->jsVars->addRoute('remove_course', ['instructor_id' => '__instructor_id__']);
        $this->jsVars->addRoute('course_info', ['id' => '__course_id__']);
        $this->jsVars->addRoute('api_web_edit_payment', ['card_id' => '__card_id__']);
        $this->jsVars->addRoute('add_package_course', ['card_id' => '__card_id__', 'credit_id' => '__credit_id__']);
        $this->jsVars->addRoute('remove_package_course', ['card_id' => '__card_id__', 'credit_id' => '__credit_id__']);

        //API
    }
}
