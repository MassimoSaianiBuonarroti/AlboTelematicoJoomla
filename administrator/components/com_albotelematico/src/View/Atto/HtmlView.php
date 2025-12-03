<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\View\Atto;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View singolo atto (form di modifica)
 */
class HtmlView extends BaseHtmlView
{
    public $form;
    public $item;
    public $state;

    public function display($tpl = null)
    {
        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void
    {
        ToolbarHelper::title('Albo telematico - Atto', 'file');

        // Applica = salva e resta nel form
        ToolbarHelper::apply('atto.apply');

        // Salva = salva e torna alla lista
        ToolbarHelper::save('atto.save');

        // Salva & nuovo (se ti può servire)
        ToolbarHelper::save2new('atto.save2new');

        // Chiudi
        ToolbarHelper::cancel('atto.cancel', 'JTOOLBAR_CLOSE');
    }
}
