<?php
\defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;

/** @var AlboTelematico\Component\Albotelematico\Administrator\View\Categorie\HtmlView $this */

$items      = $this->items ?? [];
$pagination = $this->pagination ?? null;
$state      = $this->state ?? null;

// valori dei filtri correnti (se lo state è disponibile)
$filterSearch = '';
$filterState  = '';

if ($state) {
    $filterSearch = (string) $state->get('filter.search');
    $filterState  = (string) $state->get('filter.state');
}
?>

<form action="<?php echo Route::_('index.php?option=com_albotelematico&view=categorie'); ?>"
      method="post" name="adminForm" id="adminForm">

    <h1>Albo telematico - Categorie</h1>

    <div class="well" style="padding:10px; margin-bottom:15px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <div>
                <label for="filter_search">Cerca</label><br>
                <input type="text"
                       name="filter_search"
                       id="filter_search"
                       value="<?php echo htmlspecialchars($filterSearch, ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="Nome categoria" />
            </div>

            <div>
                <label for="filter_state">Stato</label><br>
                <select name="filter_state" id="filter_state">
                    <option value="">- Tutti -</option>
                    <option value="1" <?php echo $filterState === '1' ? 'selected="selected"' : ''; ?>>Pubblicata</option>
                    <option value="0" <?php echo $filterState === '0' ? 'selected="selected"' : ''; ?>>Non pubblicata</option>
                </select>
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Filtra</button>
                <a class="btn btn-secondary"
                   href="<?php echo Route::_('index.php?option=com_albotelematico&view=categorie'); ?>">
                    Pulisci filtri
                </a>
            </div>
        </div>
    </div>

    <?php if (empty($items)) : ?>
        <p>Non ci sono categorie inserite.</p>
    <?php else : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="1%">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>
                    <th width="5%">ID</th>
                    <th>Nome categoria</th>
                    <th width="10%">Ordine</th>
                    <th width="10%">Stato</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $i => $item) : ?>
                    <tr>
                        <td>
                            <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td><?php echo (int) $item->id; ?></td>
                        <td>
                            <a href="<?php echo Route::_('index.php?option=com_albotelematico&task=categoria.edit&id=' . (int) $item->id); ?>">
                                <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </td>
                        <td><?php echo (int) $item->ordering; ?></td>
                        <td><?php echo $item->state ? 'Pubblicata' : 'Non pubblicata'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($pagination) : ?>
            <div class="pagination">
                <?php echo $pagination->getListFooter(); ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo HTMLHelper::_('form.token'); ?>
</form>
