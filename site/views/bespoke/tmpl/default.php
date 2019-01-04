<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_bes[oke]
 *
 * @copyright   Copyright (C) 2018 - Andy Kirk.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

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
    
    <p>Loading bespoke modules is all I do.</p>
    
    <?php echo JHtml::_('content.prepare', '{loadposition bespoke}'); ?>

</div>
