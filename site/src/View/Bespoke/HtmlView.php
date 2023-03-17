<?php
/**
 * @package    Joomla.Site
 * @subpackage com_bespoke
 *
 * @copyright Copyright (C) NPEU 2018.
 * @license   MIT License; see LICENSE.md
 */
use Joomla\Registry\Registry; // for new Registry

defined('_JEXEC') or die;

// Import Joomla view library
jimport('joomla.application.component.view');

/**
 * HTML View class for the Bespoke Component
 */
class BespokeViewBespoke extends JViewLegacy
{
    /**
     * Execute and display a template script.
     *
     * @param string $tpl The name of the template file to parse; automatically searches through the template paths.
     *
     * @return mixed  A string if successful, otherwise an Error object.
     */
    public function display($tpl = null)
    {
        $app     = JFactory::getApplication();
        $uri     = JUri::getInstance();
        $error   = null;

        // Get some data from the model
        // $areas      = $this->get('areas');
        // $state      = $this->get('state');

        $params     = $app->getParams();

        /*
        if (!$app->getMenu()->getActive())
        {
            $params->set('page_title', JText::_('COM_SEARCH_SEARCH'));
        }
        */

        $title = $params->get('page_title');

        if ($app->get('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);

        if ($params->get('menu-meta_description')) {
            $this->document->setDescription($params->get('menu-meta_description'));
        }

        if ($params->get('menu-meta_keywords')) {
            $this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
        }

        if ($params->get('robots')) {
            $this->document->setMetadata('robots', $params->get('robots'));
        }

        // Check for layout override
        $active = JFactory::getApplication()->getMenu()->getActive();

        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }

        // Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
        $this->params        = &$params;
        $this->error         = $error;
        $this->action        = $uri;

        $this->blocks = false;
        $this->article = false;

        $use_blocks          = (bool) $this->params->get('use_blocks', true);

        if ($use_blocks) {
            $this->blocks = $this->params->get('blocks');
        } else {
            if (!empty($this->params->get('article_alias')) && !empty($this->params->get('category_path'))) {
                $db = JFactory::getDBO();
                $query = '
                    SELECT con.id
                    FROM #__content as con
                    JOIN #__categories as cat ON con.catid = cat.id
                    WHERE con.alias = "' . $this->params->get('article_alias') . '"
                    AND cat.path = "' . $this->params->get('category_path') . '";
                    ';
                $db->setQuery($query);
                $article_id = $db->loadResult();

                JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
                $model = JModelLegacy::getInstance('Article', 'ContentModel', ['ignore_request'=>true]);
                $params = new Registry;
                $model->setState('params', $params); // params (even empty) is *required* for model
                $this->article = $model->getItem((int) $article_id);
            }
        }
        // Check for errors.
        $errors = $this->get('Errors');

        if ($errors && count($errors)) {
            JLog::add(implode('<br />', $errors), JLog::WARNING, 'jerror');

            return false;
        }
        // Display the view
        parent::display($tpl);
    }
}
