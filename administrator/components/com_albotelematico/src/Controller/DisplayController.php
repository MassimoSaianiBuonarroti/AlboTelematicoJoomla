<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Controller principale per il backend di com_albotelematico
 */
class DisplayController extends BaseController
{
    /**
     * Vista di default se non viene specificato altro
     *
     * @var string
     */
    protected $default_view = 'atti';

    /**
     * Metodo display standard
     */
    public function display($cachable = false, $urlparams = [])
    {
        $app = Factory::getApplication();

        // Recupera la view richiesta o usa quella di default
        $view = $app->input->getCmd('view', $this->default_view);
        $app->input->set('view', $view);

        return parent::display($cachable, $urlparams);
    }
}
