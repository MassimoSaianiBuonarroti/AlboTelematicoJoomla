<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
    protected $default_view = 'atti';

    public function display($cachable = false, $urlparams = [])
    {
        $app = Factory::getApplication();
        $user = $app->getIdentity();

        if (!$user->authorise('core.manage', 'com_albotelematico')) {
            throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 403);
        }

        $view = $app->input->getCmd('view', $this->default_view);
        $app->input->set('view', $view);

        return parent::display($cachable, $urlparams);
    }
}
