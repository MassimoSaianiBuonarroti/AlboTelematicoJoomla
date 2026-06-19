<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\View\Atti;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

class HtmlView extends BaseHtmlView
{
    public $items;
    public $pagination;
    public $state;

    public function display($tpl = null)
    {
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void
    {
        ToolbarHelper::title('Albo Telematico - Atti', 'stack');
        ToolbarHelper::addNew('atto.add');
        ToolbarHelper::editList('atto.edit');
        ToolbarHelper::deleteList(
            'Sei sicuro di voler eliminare gli atti selezionati?',
            'atti.delete'
        );
        ToolbarHelper::link(
            'index.php?option=com_albotelematico&view=categorie',
            'Categorie',
            'icon-folder'
        );
        ToolbarHelper::link(
            'index.php?option=com_config&view=component&component=com_albotelematico',
            'Permessi',
            'icon-options'
        );
        ToolbarHelper::preferences('com_albotelematico');
    }
}
