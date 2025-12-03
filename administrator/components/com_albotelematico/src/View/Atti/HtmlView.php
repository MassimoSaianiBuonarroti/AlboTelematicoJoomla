<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\View\Atti;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;

/**
 * View backend "Atti" - lista
 */
class HtmlView extends BaseHtmlView
{
    public $items;
    public $pagination;
    public $state;

    public function display($tpl = null)
    {
        // Recupera dati dal Model
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');

        // In caso di errori dal Model
        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        $this->addToolbar();

        parent::display($tpl);
    }

        protected function addToolbar(): void
        {
            ToolbarHelper::title('Albo telematico - Atti', 'stack');

            // Nuovo atto (usa AttoController)
            ToolbarHelper::addNew('atto.add');

            // Modifica atto selezionato (usa AttoController)
            ToolbarHelper::editList('atto.edit');

            // Elimina atti selezionati (usa AttiController)
            ToolbarHelper::deleteList(
                'Sei sicuro di voler eliminare gli atti selezionati?',
                'atti.delete'
            );

            // Vai alle categorie
            \Joomla\CMS\Toolbar\ToolbarHelper::link(
                'index.php?option=com_albotelematico&view=categorie',
                'Categorie',
                'icon-folder'
            );
        }


    
}
