<?php
namespace NPEU\Component\Bespoke\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\GenericDataException;
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
        $q->select($db->qn(['m.id', 'm.title']));
        $q->join('LEFT', $db->qn('#__modules_menu', 'mm') . ' ON ' . $db->qn('m.id') . ' = ' . $db->qn('mm.moduleid'));
        $q->from($db->qn('#__modules', 'm'));
        $q->where([
            $db->qn('m.position') . ' = ' . $db->q('bespoke'),
            $db->qn('m.published') . ' = 1',
            $db->qn('mm.menuid') . ' = ' . $db->q($id)
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
}