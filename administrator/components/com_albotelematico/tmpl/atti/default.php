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

// carichiamo le categorie per il filtro
$db    = Factory::getContainer()->get('DatabaseDriver');
$query = $db->getQuery(true)
    ->select(['id', 'title'])
    ->from($db->quoteName('#__albo_categorie'))
    ->where($db->quoteName('state') . ' = 1')
    ->order($db->quoteName('ordering') . ', ' . $db->quoteName('title'));
$db->setQuery($query);
$categories = $db->loadObjectList();
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
                    <th>ID</th>
                    <th>Nome atto</th>
                    <th>Allegato</th>
                    <th>N. documento</th>
                    <th>N. albo</th>
                    <th>Data documento</th>
                    <th>Inizio pubb.</th>
                    <th>Fine pubb.</th>
                    <th>Categoria</th>
                    <th>Stato</th>
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
                    <td>
                        <?php echo (int) $item->albo_number; ?>
                        <?php if (!empty($item->albo_year)) : ?>
                            / <?php echo (int) $item->albo_year; ?>
                        <?php endif; ?>
                    </td>

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
    <?php echo HTMLHelper::_('form.token'); ?>

</form>
