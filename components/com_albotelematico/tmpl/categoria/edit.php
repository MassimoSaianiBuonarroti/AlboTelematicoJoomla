<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

/** @var AlboTelematico\Component\Albotelematico\Administrator\View\Categoria\HtmlView $this */

$form = $this->form;
$item = $this->item;

$action = 'index.php?option=com_albotelematico&view=categoria';
if (!empty($item->id)) {
    $action .= '&id=' . (int) $item->id;
}
?>

<form action="<?php echo Route::_($action); ?>"
      method="post"
      name="adminForm"
      id="adminForm">

    <div class="row">
        <div class="col-lg-9">
            <fieldset>
                <legend>Dati categoria</legend>

                <?php echo $form->renderField('title'); ?>
                <?php echo $form->renderField('ordering'); ?>
                <?php echo $form->renderField('state'); ?>

                <!-- 👇 ID nascosto, fondamentale per l'UPDATE -->
                <?php echo $form->getInput('id'); ?>
            </fieldset>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
</form>
