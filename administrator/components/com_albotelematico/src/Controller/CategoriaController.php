<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

class CategoriaController extends FormController
{
    protected $view_list = 'categorie';
    protected $view_item = 'categoria';

    public function save($key = null, $urlVar = null)
    {
        $data = $this->input->get('jform', [], 'array');

        // Se l'ID non arriva nel jform, proviamo a prenderlo dall'URL
        if (empty($data['id'])) {
            $id = $this->input->getInt('id');
            if ($id) {
                $data['id'] = $id;
            }
        }

        $model = $this->getModel();

        if (!$model->save($data)) {
            $this->setMessage($model->getError(), 'error');
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=categoria&layout=edit', false)
            );
            return false;
        }

        $this->setMessage('Categoria salvata correttamente');

        $id   = (int) $model->getState($model->getName() . '.id');
        $task = $this->getTask();

        // Applica = resta sul form
        if ($task === 'apply') {
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=categoria&layout=edit&id=' . $id, false)
            );
            return true;
        }

        // Salva & nuovo
        if ($task === 'save2new') {
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=categoria&layout=edit', false)
            );
            return true;
        }

        // Salva normale → torna alla lista
        $this->setRedirect(
            Route::_('index.php?option=com_albotelematico&view=categorie', false)
        );

        return true;
    }
}
