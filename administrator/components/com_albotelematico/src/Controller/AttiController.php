<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\AdminController;

/**
 * Controller per la lista degli atti
 * Gestisce azioni di massa: delete, publish, ecc.
 */
class AttiController extends AdminController
{
    /**
     * Nome della view di lista
     *
     * @var string
     */
    protected $view_list = 'atti';
}
