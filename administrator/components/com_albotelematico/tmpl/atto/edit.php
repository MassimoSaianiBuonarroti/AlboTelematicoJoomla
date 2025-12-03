<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var AlboTelematico\Component\Albotelematico\Administrator\View\Atto\HtmlView $this */

$form = $this->form;
$item = $this->item;

// Decodifica allegati
$attachments = [];

if (!empty($item->file)) {
    $decoded = json_decode($item->file, true);

    if (is_array($decoded)) {
        $attachments = $decoded;
    } else {
        $attachments = [$item->file];
    }
}

// costruiamo la action del form includendo l'ID se esiste
$action = 'index.php?option=com_albotelematico&view=atto';
if (!empty($item->id)) {
    $action .= '&id=' . (int) $item->id;
}
?>

<form action="<?php echo Route::_($action); ?>"
      method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">

    <div class="row">
        <div class="col-lg-9">
            <fieldset>
                <legend>Dati atto</legend>

                <?php echo $form->renderField('title'); ?>
                <?php echo $form->renderField('document_number'); ?>
                <?php echo $form->renderField('albo_number'); ?>
                <?php echo $form->renderField('document_date'); ?>
                <?php echo $form->renderField('publish_start'); ?>
                <?php echo $form->renderField('publish_end'); ?>
                <?php echo $form->renderField('category'); ?>
                <?php echo $form->renderField('state'); ?>
                <?php echo $form->renderField('file'); ?>

                <?php if (!empty($attachments)) : ?>
                    <div class="mt-2">
                        <strong>Allegati esistenti:</strong>
                        <ul>
                            <?php foreach ($attachments as $path) : ?>
                                <li>
                                    <label>
                                        <input type="checkbox"
                                            name="delete_attachments[]"
                                            value="<?php echo htmlspecialchars($path, ENT_QUOTES, 'UTF-8'); ?>">
                                        Elimina
                                    </label>
                                    &nbsp;
                                    <a href="<?php echo htmlspecialchars(Uri::root() . $path, ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                        <?php echo htmlspecialchars(basename($path), ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p><em>Spunta "Elimina" sugli allegati da cancellare e clicca Salva/Applica.</em></p>
                    </div>
                <?php endif; ?>


                <!-- Forziamo anche l'ID dentro jform -->
                <input type="hidden" name="jform[id]" value="<?php echo (int) ($item->id ?? 0); ?>" />
            </fieldset>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo \Joomla\CMS\HTML\HTMLHelper::_('form.token'); ?>
</form>
