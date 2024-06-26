<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bespoke
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Bespoke\Site\View\Bespoke;

defined('_JEXEC') or die;

#use Joomla\CMS\Helper\TagsHelper;
#use Joomla\CMS\Language\Text;
#use Joomla\CMS\Plugin\PluginHelper;
#use Joomla\CMS\Router\Route;
#use Joomla\Event\Event;
use Joomla\CMS\Factory;
use Joomla\CMS\Log\Log;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;

class HtmlView extends BaseHtmlView {

    public function display($template = null)
    {

        $app    = Factory::getApplication();
        $uri    = Uri::getInstance();
        $params = $app->getParams();


        $title = $params->get('page_title');

        #echo '<pre>'; var_dump($title); echo '<pre>'; exit;

        // ...
        $error   = null;

        if ($app->get('sitename_pagetitles', 0) == 1) {
            $title = \Joomla\CMS\Language\Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
        } elseif ($app->get('sitename_pagetitles', 0) == 2) {
            $title = \Joomla\CMS\Language\Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
        }

        $this->document->setTitle($title);
        #echo '<pre>'; var_dump($title); echo '<pre>'; exit;

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
        $active = $app->getMenu()->getActive();

        #echo '<pre>'; var_dump($active); echo '<pre>'; exit;

        if (isset($active->query['layout'])) {
            $this->setLayout($active->query['layout']);
        }

        // Escape strings for HTML output
        $this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx', ''));
        $this->params        = $params;
        $this->error         = $error;
        $this->action        = $uri;

        $this->blocks = false;
        $this->article = false;

        $use_blocks          = (bool) $this->params->get('use_blocks', true);
        #echo '<pre>'; var_dump($use_blocks); echo '<pre>'; exit;

        if ($use_blocks) {
            $this->blocks = $this->params->get('blocks');
        } else {
            if (!empty($this->params->get('article_alias')) && !empty($this->params->get('category_path'))) {
                $db = \Joomla\CMS\Factory::getContainer()->get(DatabaseInterface::class);
                $query = '
                    SELECT con.id
                    FROM #__content as con
                    JOIN #__categories as cat ON con.catid = cat.id
                    WHERE con.alias = "' . $this->params->get('article_alias') . '"
                    AND cat.path = "' . $this->params->get('category_path') . '";
                    ';
                $db->setQuery($query);
                $article_id = $db->loadResult();

                $app = Factory::getApplication();
                $article_model = $app->bootComponent('com_content')->getMVCFactory()->createModel('Article', 'Administrator', ['ignore_request' => true]);

                #JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');
                #$article_model = JModelLegacy::getInstance('Article', 'ContentModel', ['ignore_request'=>true]);
                #$params = new Registry;
                #$article_model->setState('params', $params); // params (even empty) is *required* for model
                $this->article = $article_model->getItem((int) $article_id);

                // When a 'normal' article is loaded it has a params object filled with all sorts.
                // Loading it via this model doesn't and I'm not why. Perhaps it's the wrong model
                // for the job? Without the params object many other places in the tempalte fail
                // because it's assumed to be there. So just putting this in for now pending a more
                // thorough probe into what's going on:
                $this->article->params = new Registry;
            }
        };

        // Check for errors.
        $errors = $this->get('Errors', false);

        if (!empty($errors)) {
            Log::add(implode('<br />', $errors), Log::WARNING, 'jerror');

            return false;
        }

        // Call the parent display to display the layout file
        parent::display($template);
    }

}