<?php

namespace AlboTelematico\Component\Albotelematico\Administrator\Controller;

\defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Router\Route;

/**
 * Controller per il singolo atto
 */
class AttoController extends FormController
{
    protected $view_list = 'atti';
    protected $view_item = 'atto';

    /**
     * Salvataggio dell'atto
     */
    public function save($key = null, $urlVar = null)
    {
        $data  = $this->input->get('jform', [], 'array');

        // Se l'ID non è in jform, prova a leggerlo dall'URL
        if (empty($data['id'])) {
            $id = $this->input->getInt('id');
            if ($id) {
                $data['id'] = $id;
            }
        }

        $model = $this->getModel();

        if (!$model->save($data)) {
            // Errore nel salvataggio: torniamo al form
            $this->setMessage($model->getError(), 'error');
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=atto&layout=edit', false)
            );
            return false;
        }

        // Tutto ok
        $this->setMessage('Atto salvato correttamente');

        // ID dell'atto appena salvato
        $id = (int) $model->getState($model->getName() . '.id');
        $task = $this->getTask();

        // Se ho cliccato "Applica" → resto sul form
        if ($task === 'apply') {
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=atto&layout=edit&id=' . $id, false)
            );
            return true;
        }

        // Se ho cliccato "Salva & Nuovo"
        if ($task === 'save2new') {
            $this->setRedirect(
                Route::_('index.php?option=com_albotelematico&view=atto&layout=edit', false)
            );
            return true;
        }

        // Task "save" normale → torna alla lista
        $this->setRedirect(
            Route::_('index.php?option=com_albotelematico&view=atti', false)
        );

        return true;
    }
}
