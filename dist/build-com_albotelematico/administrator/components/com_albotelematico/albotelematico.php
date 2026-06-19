<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_albotelematico
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;

$app   = Factory::getApplication();
$input = $app->input;

// Task richiesto (es. "", "display", "atto.add", "atto.edit", ...)
$task = $input->getCmd('task', 'display');

// Controller di default
$controllerName = 'Display';

// Se il task è in forma "controller.metodo" (es. "atto.add")
if (strpos($task, '.') !== false)
{
    list($controllerName, $task) = explode('.', $task, 2);
    $controllerName = ucfirst($controllerName); // "atto" -> "Atto"
}
else
{
    // se il task è "display" o vuoto, restiamo su DisplayController
    $controllerName = 'Display';
}

// Crea il controller corretto tramite la MVCFactory
$controller = $app->bootComponent('com_albotelematico')
    ->getMVCFactory()
    ->createController($controllerName, 'Administrator', ['option' => 'com_albotelematico']);

// Esegui il metodo richiesto (display, add, edit, save, ...)
$controller->execute($task);
$controller->redirect();
