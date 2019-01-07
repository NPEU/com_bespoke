<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bespoke
 *
 * @copyright   Copyright (C) NPEU 2018.
 * @license     MIT License; see LICENSE.md
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Checks for the existence of Custom Fields and creates them if not present.
 *
 * @package     Joomla.Plugins
 * @subpackage  plg_projects
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class com_BespokeInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent) 
    {
        #$parent->getParent()->setRedirectURL('index.php?option=com_helloworld');
        #file_put_contents(dirname($_SERVER['DOCUMENT_ROOT']) . '/extensions/plgProjectsInstallerScript--install.txt', date('c'));
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent) 
    {
        #echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent) 
    {
        #echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * Runs just before any installation action is preformed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent) 
    {
        #file_put_contents(dirname($_SERVER['DOCUMENT_ROOT']) . '/extensions/plgProjectsInstallerScript--preflight.txt', date('c'));
    }

    /**
     * Runs right after any installation action is preformed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function postflight($type, $parent) 
    {
        #echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        #file_put_contents(dirname($_SERVER['DOCUMENT_ROOT']) . '/extensions/plgProjectsInstallerScript--postflight.txt', date('c'));
        if ($type != 'install') {
            return;
        }
        
        #file_put_contents('/_sub/j3/extensions/t.txt', 'test');

        #echo "<pre>\n"; var_dump('install'); echo "</pre>\n"; exit;
        
        
        $manifest = $parent->getParent()->getManifest();
        $name = (string) $manifest->name;
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id');
        $query->from('#__menu');
        $query->where('title = "' . strtoupper($name) . '_MENU"');
        $db->setQuery($query);
        $ids = $db->loadColumn();

        $db = JFactory::getDbo();
        $table = JTable::getInstance('menu');       
        
        if ($error = $db->getErrorMsg())
        {
            return false;
        }
        elseif (!empty($ids))
        {
            // Iterate the items to delete each one.
            foreach ($ids as $menu_id)
            {
                if (!$table->delete((int) $menu_id))
                {
                    return false;
                }
            }
            // Rebuild the whole tree
            $table->rebuild();
        }
        return true;
    }
}