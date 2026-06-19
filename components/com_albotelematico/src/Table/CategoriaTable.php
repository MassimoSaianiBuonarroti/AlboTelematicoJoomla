<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Table;

\defined('_JEXEC') or die;

use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseDriver;

class CategoriaTable extends Table
{
    public function __construct(DatabaseDriver $db)
    {
        parent::__construct('#__albo_categorie', 'id', $db);
    }
}
