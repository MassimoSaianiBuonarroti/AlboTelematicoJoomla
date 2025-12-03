<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\ListModel;

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
                'document_date', 'a.document_date',
                'publish_start', 'a.publish_start',
                'publish_end', 'a.publish_end',
                'category', 'a.category',
                'category_title', 'c.title',
                'state', 'a.state',
            ];
        }

        parent::__construct($config);
    }

    protected function populateState($ordering = 'a.document_date', $direction = 'DESC')
    {
        $app = Factory::getApplication();

        // Filtro testo
        $search = $app->getUserStateFromRequest(
            $this->context . '.filter.search',
            'filter_search',
            '',
            'string'
        );
        $this->setState('filter.search', $search);

        // Filtro categoria (ID)
        $category = $app->getUserStateFromRequest(
            $this->context . '.filter.category',
            'filter_category',
            0,
            'int'
        );
        $this->setState('filter.category', $category);

        parent::populateState($ordering, $direction);
    }

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
                    'a.document_date',
                    'a.publish_start',
                    'a.publish_end',
                    'a.category',
                    'c.title AS category_title',
                    'a.state',
                    'a.file', // 👈 IMPORTANTE
                ]
            )
            ->from($db->quoteName('#__albo_atti', 'a'))
            ->join('LEFT', $db->quoteName('#__albo_categorie', 'c') . ' ON c.id = a.category');


        // --- Filtro testo ---
        $search = $this->getState('filter.search');

        if (!empty($search)) {
            $search = '%' . $db->escape($search, true) . '%';
            $conditions = [
                $db->quoteName('a.title') . ' LIKE ' . $db->quote($search, false),
                $db->quoteName('a.document_number') . ' LIKE ' . $db->quote($search, false),
            ];
            $query->where('(' . implode(' OR ', $conditions) . ')');
        }

        // --- Filtro categoria ---
        $category = (int) $this->getState('filter.category');

        if ($category > 0) {
            $query->where($db->quoteName('a.category') . ' = ' . (int) $category);
        }

        // Ordinamento
        $orderCol  = $this->state->get('list.ordering', 'a.document_date');
        $orderDirn = $this->state->get('list.direction', 'DESC');

        $query->order($db->escape($orderCol . ' ' . $orderDirn));

        return $query;
    }
}
