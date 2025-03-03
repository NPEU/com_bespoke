<?php
namespace NPEU\Component\Bespoke\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;

#use NPEU\Component\Researchprojects\Administrator\Helper\ResearchprojectsHelper;

defined('_JEXEC') or die;

#JFormHelper::loadFieldClass('list');

/**
 * Form field for a list of brands.
 */
class AssignedmodulesField extends ListField
{
    /**
     * The form field type.
     *
     * @var     string
     */
    protected $type = 'AssignedModules';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     */
    protected function getOptions()
    {
        $options = [];
        $db = Factory::getDBO();

        $uri = Uri::getInstance();
        $id = $uri->getVar('id');

        $q = $db->getQuery(true);
        $q->select($db->quoteName(['m.id', 'm.title']));
        $q->join('LEFT', $db->quoteName('#__modules_menu', 'mm') . ' ON ' . $db->quoteName('m.id') . ' = ' . $db->quoteName('mm.moduleid'));
        $q->from($db->quoteName('#__modules', 'm'));
        $q->where([
            $db->quoteName('m.position') . ' = ' . $db->quote('bespoke'),
            $db->quoteName('m.published') . ' = 1',
            $db->quoteName('mm.menuid') . ' = ' . $db->quote($id)
        ]);
        $q->order('m.title');

        $db->setQuery($q);
        if (!$db->execute($q)) {
            throw new GenericDataException($db->stderr(), 500);
            return false;
        }

        $modules = $db->loadAssocList();

        $i = 0;
        foreach ($modules as $module) {
            $options[] = HTMLHelper::_('select.option', $module['id'], $module['title']);
            $i++;
        }

        if ($i > 0) {
            // Merge any additional options in the XML definition.
            $options = array_merge(parent::getOptions(), $options);
        } else {
            $options = parent::getOptions();
            #$options[0]->text = Text::_('COM_RESEARCHPROJECTS_BRAND_DEFAULT');
            $options[0]->text = 'Select module';
        }
        return $options;
    }

    /**
     * Method to get the field label markup for a spacer.
     * Use the label text or name from the XML element as the spacer or
     * Use a hr="true" to automatically generate plain hr markup
     *
     * @return  string  The field label markup.
     */
    protected function getLabel()
    {
        $append = false;
        $module_id = $this->value;

        if (!empty($module_id)) {
            $append = "\n" . '<br><a href="' . Route::_('/administrator/index.php?option=com_modules&task=module.edit&id=' . $module_id) .'" target="_blank">Edit current module</a>';
        }

        return parent::getLabel() . $append;
    }
}