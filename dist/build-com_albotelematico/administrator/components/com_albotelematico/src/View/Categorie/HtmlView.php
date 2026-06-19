<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\View\Categorie;

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

        $errors = $this->get('Errors');

        if (!empty($errors)) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(): void
    {
        ToolbarHelper::title('Albo Telematico - Categorie', 'folder');
        ToolbarHelper::addNew('categoria.add');
        ToolbarHelper::editList('categoria.edit');
        ToolbarHelper::publish('categorie.publish', 'JTOOLBAR_PUBLISH', true);
        ToolbarHelper::unpublish('categorie.unpublish', 'JTOOLBAR_UNPUBLISH', true);
        ToolbarHelper::deleteList('Sei sicuro di voler eliminare le categorie selezionate?', 'categorie.delete');
        ToolbarHelper::link(
            'index.php?option=com_albotelematico&view=atti',
            'Atti',
            'icon-list'
        );
        ToolbarHelper::link(
            'index.php?option=com_config&view=component&component=com_albotelematico',
            'Permessi',
            'icon-options'
        );
        ToolbarHelper::preferences('com_albotelematico');
    }
}
