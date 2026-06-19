<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;
use AlboTelematico\Component\Albotelematico\Administrator\Table\CategoriaTable;

class CategorieModel extends ListModel
{
    public function __construct($config = [])
    {
        // campi su cui è possibile ordinare
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'title', 'ordering', 'state',
            ];
        }

        parent::__construct($config);
    }

    /**
     * Indichiamo a Joomla quale tabella usare per le categorie
     */
    public function getTable($type = 'Categoria', $prefix = 'AlboTelematico\\Component\\Albotelematico\\Administrator\\Table\\', $config = [])
    {
        return new CategoriaTable(Factory::getContainer()->get('DatabaseDriver'));
    }

    protected function populateState($ordering = 'title', $direction = 'ASC')
    {
        parent::populateState($ordering, $direction);
    }

    /**
     * Query che carica la lista categorie
     */
    protected function getListQuery()
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        // Prende tutte le righe dalla tabella categorie
        $query
            ->select('*')
            ->from($db->quoteName('#__albo_categorie'));

        // Ordine base per nome categoria
        $query->order($db->quoteName('title') . ' ASC');

        return $query;
    }

    /**
     * Eliminazione delle categorie selezionate (usata da AdminController::delete)
     *
     * @param  array  $cid  lista ID selezionati
     * @return bool
     */
    public function delete(&$cid)
    {
        $cid = (array) $cid;
        $cid = array_filter(array_map('intval', $cid));

        if (empty($cid)) {
            $this->setError('Nessuna categoria selezionata per l\'eliminazione.');
            return false;
        }

        /** @var CategoriaTable $table */
        $table = $this->getTable();

        foreach ($cid as $pk) {
            if (!$table->delete($pk)) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }

        /**
     * Pubblica / sospende le categorie selezionate.
     *
     * @param  array  $cid   ID delle categorie
     * @param  int    $value 1 = pubblica, 0 = sospendi
     * @return bool
     */
    public function publish(&$cid, $value)
    {
        $cid = (array) $cid;
        $cid = array_filter(array_map('intval', $cid));

        if (empty($cid)) {
            $this->setError('Nessuna categoria selezionata.');
            return false;
        }

        $value = (int) $value;

        /** @var CategoriaTable $table */
        $table = $this->getTable();

        foreach ($cid as $pk) {
            if (!$table->load($pk)) {
                $this->setError($table->getError());
                return false;
            }

            // la colonna si chiama "state"
            $table->state = $value;

            if (!$table->store()) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }

}
