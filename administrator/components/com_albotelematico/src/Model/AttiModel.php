<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\MVC\Model\ListModel;
use AlboTelematico\Component\Albotelematico\Administrator\Table\AttoTable;

class AttiModel extends ListModel
{
    public function __construct($config = [])
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = [
                'id', 'a.id',
                'title', 'a.title',
                'document_number', 'a.document_number',
                'albo_number', 'a.albo_number',
                'albo_year', 'a.albo_year',   // 👈 aggiunto
                'document_date', 'a.document_date',
                'publish_start', 'a.publish_start',
                'publish_end', 'a.publish_end',
                'category', 'a.category',
                'state', 'a.state',
            ];
        }

        parent::__construct($config);
    }

    /**
     * Tabella usata per i singoli atti
     */
    public function getTable($type = 'Atto', $prefix = 'AlboTelematico\\Component\\Albotelematico\\Administrator\\Table\\', $config = [])
    {
        return new AttoTable(Factory::getContainer()->get('DatabaseDriver'));
    }

    /**
     * Stato (filtri, ordinamento)
     */
    protected function populateState($ordering = 'a.document_date', $direction = 'DESC')
    {
        $app = Factory::getApplication();

        // Filtro ricerca (titolo o numero documento)
        $search = $app->getUserStateFromRequest(
            $this->context . '.filter.search',
            'filter_search',
            '',
            'string'
        );
        $this->setState('filter.search', $search);

        // Filtro categoria
        $category = $app->getUserStateFromRequest(
            $this->context . '.filter.category',
            'filter_category',
            0,
            'int'
        );
        $this->setState('filter.category', $category);

        // 👇 filtro ANNO
        $year = $app->getUserStateFromRequest(
            $this->context . '.filter.year',
            'filter_year',
            0,
            'int'
        );
        $this->setState('filter.year', $year);

        parent::populateState($ordering, $direction);
    }

    /**
     * Query per la lista atti
     */
    protected function getListQuery()
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true);

        $query
            ->select(
                [
                    'a.id',
                    'a.title',
                    'a.document_number',
                    'a.albo_number',
                    'a.albo_year',
                    'a.document_date',
                    'a.publish_start',
                    'a.publish_end',
                    'a.category',
                    'c.title AS category_title',
                    'a.state',
                    'a.file',
                ]
            )
            ->from($db->quoteName('#__albo_atti', 'a'))
            ->join(
                'LEFT',
                $db->quoteName('#__albo_categorie', 'c') . ' ON ' . $db->quoteName('c.id') . ' = ' . $db->quoteName('a.category')
            );

        // Filtro ricerca
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            $search = '%' . $db->escape($search, true) . '%';
            $conditions = [
                $db->quoteName('a.title') . ' LIKE ' . $db->quote($search, false),
                $db->quoteName('a.document_number') . ' LIKE ' . $db->quote($search, false),
            ];
            $query->where('(' . implode(' OR ', $conditions) . ')');
        }

        // Filtro categoria
        $category = (int) $this->getState('filter.category', 0);
        if ($category > 0) {
            $query->where($db->quoteName('a.category') . ' = ' . (int) $category);
        }

        // Filtro ANNO (albo_year)
        $year = (int) $this->getState('filter.year', 0);
        if ($year > 0) {
            $query->where($db->quoteName('a.albo_year') . ' = ' . (int) $year);
        }

        // Ordinamento
        $orderCol  = $this->state->get('list.ordering', 'a.document_date');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }

    /**
     * Eliminazione atti selezionati (usata da AdminController::delete)
     * Cancella anche gli eventuali allegati PDF dal filesystem.
     *
     * @param  array  $cid  lista ID selezionati
     * @return bool
     */
    public function delete(&$cid)
    {
        $cid = (array) $cid;
        $cid = array_filter(array_map('intval', $cid));

        if (empty($cid)) {
            $this->setError('Nessun atto selezionato per l\'eliminazione.');
            return false;
        }

        /** @var AttoTable $table */
        $table = $this->getTable();

        foreach ($cid as $pk) {
            // Carichiamo il record per sapere quali allegati ha
            if (!$table->load($pk)) {
                $this->setError($table->getError());
                return false;
            }

            // Cancella eventuali allegati (singolo path o JSON)
            if (!empty($table->file)) {
                $attachments = [];
                $decoded     = json_decode($table->file, true);

                if (is_array($decoded)) {
                    $attachments = $decoded;
                } else {
                    $attachments = [$table->file];
                }

                foreach ($attachments as $path) {
                    $fullPath = JPATH_ROOT . '/' . $path;
                    if (File::exists($fullPath)) {
                        File::delete($fullPath);
                    }
                }
            }

            // Cancella il record dalla tabella
            if (!$table->delete($pk)) {
                $this->setError($table->getError());
                return false;
            }
        }

        return true;
    }
}
