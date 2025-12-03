<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Field;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;

/**
 * Campo select per le categorie dell'albo
 */
class AlboCategoryField extends ListField
{
    protected $type = 'AlboCategory';

    protected function getOptions()
    {
        $options = parent::getOptions();

        $db    = Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select(['id', 'title'])
            ->from($db->quoteName('#__albo_categorie'))
            ->where($db->quoteName('state') . ' = 1')
            ->order($db->quoteName('ordering') . ', ' . $db->quoteName('title'));

        $db->setQuery($query);
        $rows = $db->loadObjectList();

        foreach ($rows as $row)
        {
            // 👇 ADESSO SALVIAMO L'ID, NON IL TITOLO
            $options[] = HTMLHelper::_('select.option', $row->id, $row->title);
        }

        return $options;
    }

}
