<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Model;

\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\AdminModel;
use AlboTelematico\Component\Albotelematico\Administrator\Table\CategoriaTable;

class CategoriaModel extends AdminModel
{
    public function getTable($type = 'Categoria', $prefix = 'AlboTelematico\\Component\\Albotelematico\\Administrator\\Table\\', $config = [])
    {
        return new CategoriaTable(Factory::getContainer()->get('DatabaseDriver'));
    }

    public function getForm($data = [], $loadData = true)
    {
        $form = $this->loadForm(
            'com_albotelematico.categoria',
            'categoria',
            ['control' => 'jform', 'load_data' => $loadData]
        );

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = Factory::getApplication()->getUserState(
            'com_albotelematico.edit.categoria.data',
            []
        );

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
