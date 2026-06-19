<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Table\Table;

class Com_AlbotelematicoInstallerScript
{
    public function install($parent): bool
    {
        return $this->ensureAttachmentFolder();
    }

    public function update($parent): bool
    {
        return $this->ensureAttachmentFolder();
    }

    public function postflight($type, $parent): bool
    {
        return $this->ensureAttachmentFolder() && $this->ensureAdministratorMenu();
    }

    private function ensureAttachmentFolder(): bool
    {
        $path = JPATH_ROOT . '/images/albo_atti';

        if (!Folder::exists($path)) {
            return Folder::create($path);
        }

        return true;
    }

    private function ensureAdministratorMenu(): bool
    {
        $db = Factory::getContainer()->get('DatabaseDriver');

        $extensionId = (int) $db->setQuery(
            $db->getQuery(true)
                ->select($db->quoteName('extension_id'))
                ->from($db->quoteName('#__extensions'))
                ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
                ->where($db->quoteName('element') . ' = ' . $db->quote('com_albotelematico'))
        )->loadResult();

        if ($extensionId <= 0) {
            return true;
        }

        $menuId = (int) $db->setQuery(
            $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#__menu'))
                ->where($db->quoteName('client_id') . ' = 1')
                ->where($db->quoteName('link') . ' = ' . $db->quote('index.php?option=com_albotelematico'))
        )->loadResult();

        $menu = Table::getInstance('Menu', 'Joomla\\CMS\\Table\\');

        if ($menuId > 0) {
            $menu->load($menuId);
        } else {
            $menu->setLocation(1, 'last-child');
        }

        $menu->bind([
            'menutype' => 'main',
            'title' => 'COM_ALBOTELEMATICO',
            'alias' => 'com-albotelematico',
            'link' => 'index.php?option=com_albotelematico',
            'type' => 'component',
            'published' => 1,
            'parent_id' => 1,
            'component_id' => $extensionId,
            'access' => 1,
            'img' => 'class:stack',
            'template_style_id' => 0,
            'params' => '{}',
            'home' => 0,
            'language' => '*',
            'client_id' => 1,
        ]);

        return $menu->check() && $menu->store();
    }
}
