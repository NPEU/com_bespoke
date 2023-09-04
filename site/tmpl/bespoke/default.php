<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bespoke
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

#use Joomla\CMS\Language\Text;
#use Joomla\CMS\Factory;
#use Joomla\CMS\Router\Route;
#use Joomla\CMS\Layout\LayoutHelper;
#use Joomla\CMS\Layout\FileLayout;
#use Joomla\CMS\Language\Multilanguage;
#use Joomla\CMS\Session\Session;
#use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;

defined('_JEXEC') or die;
?>
<div class="search<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <h1 class="page-title">
            <?php if ($this->escape($this->params->get('page_heading'))) : ?>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            <?php else : ?>
                <?php echo $this->escape($this->params->get('page_title')); ?>
            <?php endif; ?>
        </h1>
    <?php endif; ?>

    <?php /* <jdoc:include type="module" name="bespoke" /> */ ?>

    <?php echo JHtml::_('content.prepare', '{loadposition bespoke}'); ?>

    <?php
    #$renderer   = $this->document->loadRenderer('modules');
    #$position   = 'bespoke';
    #$options   = array('style' => 'raw');
    ?>
</div>
