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
	
    <jdoc:include type="module" name="bespoke" />
    
    <?php echo JHtml::_('content.prepare', '{loadposition bespoke}'); ?>
    
    <?php
    $document = JFactory::getDocument();
    $renderer   = $document->loadRenderer('modules');
    $position   = 'bespoke';
    $options   = array('style' => 'raw');
    ?>
</div>
