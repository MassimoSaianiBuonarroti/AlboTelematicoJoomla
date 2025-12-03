<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\View\Atto;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    public $form;
    public $item;
    public $state;

    public function display($tpl = null)
    {
        // 👇 forza sempre il layout "edit"
        $this->setLayout('edit');

        $this->form  = $this->get('Form');
        $this->item  = $this->get('Item');
        $this->state = $this->get('State');

        $errors = $this->get('Errors');
        if (!empty($errors)) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void
    {
        ToolbarHelper::title('Albo telematico - Atto', 'file');

        ToolbarHelper::apply('atto.apply');
        ToolbarHelper::save('atto.save');
        ToolbarHelper::save2new('atto.save2new');
        ToolbarHelper::cancel('atto.cancel', 'JTOOLBAR_CLOSE');
    }
}
