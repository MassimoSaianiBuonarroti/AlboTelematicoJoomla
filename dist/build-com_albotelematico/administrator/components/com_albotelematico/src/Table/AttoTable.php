<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class AttoTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        // PK = id, tabella = #__albo_atti
        parent::__construct('#__albo_atti', 'id', $db);
    }

    public function store($updateNulls = false)
    {
        // Se è un nuovo record (id vuoto) e il numero albo non è impostato
        if (empty($this->id) && (empty($this->albo_number) || (int) $this->albo_number === 0))
        {
            $db    = $this->getDbo();
            $query = $db->getQuery(true)
                ->select('MAX(albo_number)')
                ->from($db->quoteName('#__albo_atti'));

            $db->setQuery($query);
            $max = (int) $db->loadResult();

            $this->albo_number = $max + 1;
        }

        return parent::store($updateNulls);
    }
}
