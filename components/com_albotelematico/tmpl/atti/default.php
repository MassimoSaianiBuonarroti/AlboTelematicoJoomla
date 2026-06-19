<?php
\defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

/** @var AlboTelematico\Component\Albotelematico\Administrator\View\Atti\HtmlView $this */

$items      = $this->items;
$pagination = $this->pagination;
$state      = $this->state;

// valori dei filtri correnti
$filterSearch   = $state->get('filter.search');
$filterCategory = (int) $state->get('filter.category');
$filterYear     = (int) $state->get('filter.year');

// ordinamento corrente
$listOrder = $state->get('list.ordering', 'a.document_date');
$listDirn  = $state->get('list.direction', 'DESC');

// carichiamo le categorie per il filtro
$db    = Factory::getContainer()->get('DatabaseDriver');
$query = $db->getQuery(true)
    ->select(['id', 'title'])
    ->from($db->quoteName('#__albo_categorie'))
    ->where($db->quoteName('state') . ' = 1')
    ->order($db->quoteName('ordering') . ', ' . $db->quoteName('title'));
$db->setQuery($query);
$categories = $db->loadObjectList();

// carichiamo gli anni disponibili (albo_year) per il filtro
$queryYears = $db->getQuery(true)
    ->select('DISTINCT ' . $db->quoteName('albo_year') . ' AS year')
    ->from($db->quoteName('#__albo_atti'))
    ->where($db->quoteName('albo_year') . ' > 0')
    ->order($db->quoteName('albo_year') . ' DESC');
$db->setQuery($queryYears);
$years = $db->loadColumn();
?>

<form action="<?php echo Route::_('index.php?option=com_albotelematico&view=atti'); ?>"
      method="post" name="adminForm" id="adminForm">

    <h1>Albo telematico - Atti</h1>

    <!-- FILTRI -->
    <div class="well" style="padding:10px; margin-bottom:15px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            <div>
                <label for="filter_search">Cerca</label><br>
                <input type="text"
                       name="filter_search"
                       id="filter_search"
                       value="<?php echo htmlspecialchars($filterSearch, ENT_QUOTES, 'UTF-8'); ?>"
                       placeholder="Titolo o numero documento" />
            </div>

            <div>
                <label for="filter_category">Categoria</label><br>
                <select name="filter_category" id="filter_category">
                    <option value="0">- Tutte -</option>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?php echo (int) $cat->id; ?>"
                            <?php echo $filterCategory === (int) $cat->id ? 'selected="selected"' : ''; ?>>
                            <?php echo htmlspecialchars($cat->title, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="filter_year">Anno</label><br>
                <select name="filter_year" id="filter_year">
                    <option value="0">- Tutti -</option>
                    <?php foreach ($years as $year) : ?>
                        <option value="<?php echo (int) $year; ?>"
                            <?php echo $filterYear === (int) $year ? 'selected="selected"' : ''; ?>>
                            <?php echo (int) $year; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <button class="btn btn-primary" type="submit">Filtra</button>
                <a class="btn btn-secondary"
                   href="<?php echo Route::_('index.php?option=com_albotelematico&view=atti'); ?>">
                    Pulisci filtri
                </a>
            </div>
        </div>
    </div>

    <!-- LISTA ATTI -->
    <?php if (empty($items)) : ?>
        <p>Non ci sono atti inseriti.</p>
    <?php else : ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th width="1%">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'ID', 'a.id', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Nome atto', 'a.title', $listDirn, $listOrder); ?>
                    </th>
                    <th>Allegato</th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'N. documento', 'a.document_number', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'N. albo', 'a.albo_number', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Anno', 'a.albo_year', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Data documento', 'a.document_date', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Inizio pubb.', 'a.publish_start', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Fine pubb.', 'a.publish_end', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Categoria', 'c.title', $listDirn, $listOrder); ?>
                    </th>
                    <th>
                        <?php echo HTMLHelper::_('grid.sort', 'Stato', 'a.state', $listDirn, $listOrder); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $i => $item) : ?>
                <?php
                // Decodifica allegati (supporta sia JSON che singolo path)
                $attachments = [];
                if (!empty($item->file)) {
                    $decoded = json_decode($item->file, true);
                    if (is_array($decoded)) {
                        $attachments = $decoded;
                    } else {
                        $attachments = [$item->file];
                    }
                }
                $attachmentsCount = count($attachments);
                ?>
                <tr>
                    <td>
                        <?php echo HTMLHelper::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td><?php echo (int) $item->id; ?></td>
                    <td>
                        <a href="<?php echo Route::_('index.php?option=com_albotelematico&task=atto.edit&id=' . (int) $item->id); ?>">
                            <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                        </a>
                    </td>
                    <td>
                        <?php if ($attachmentsCount === 1) : ?>
                            <a href="<?php echo htmlspecialchars(Uri::root() . $attachments[0], ENT_QUOTES, 'UTF-8'); ?>" target="_blank">
                                PDF
                            </a>
                        <?php elseif ($attachmentsCount > 1) : ?>
                            <?php echo $attachmentsCount; ?> allegati
                        <?php else : ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item->document_number, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo (int) $item->albo_number; ?></td>
                    <td><?php echo (int) $item->albo_year; ?></td>
                    <td>
                        <?php echo $item->document_date
                            ? HTMLHelper::_('date', $item->document_date, 'd-m-Y')
                            : ''; ?>
                    </td>
                    <td>
                        <?php echo $item->publish_start
                            ? HTMLHelper::_('date', $item->publish_start, 'd-m-Y')
                            : ''; ?>
                    </td>
                    <td>
                        <?php echo $item->publish_end
                            ? HTMLHelper::_('date', $item->publish_end, 'd-m-Y')
                            : ''; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item->category_title ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo $item->state ? 'Pubblicato' : 'Non pubblicato'; ?></td>
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

    <!-- campi nascosti per ordinamento -->
    <input type="hidden" name="filter_order" value="<?php echo htmlspecialchars($listOrder, ENT_QUOTES, 'UTF-8'); ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo htmlspecialchars($listDirn, ENT_QUOTES, 'UTF-8'); ?>" />

    <?php echo HTMLHelper::_('form.token'); ?>

</form>
