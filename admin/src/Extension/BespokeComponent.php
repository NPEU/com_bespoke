<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_Bespoke
 *
 * @copyright   Copyright (C) NPEU 2023.
 * @license     MIT License; see LICENSE.md
 */

namespace NPEU\Component\Bespoke\Administrator\Extension;

defined('JPATH_PLATFORM') or die;

#use Joomla\CMS\Association\AssociationServiceInterface;
#use Joomla\CMS\Association\AssociationServiceTrait;
#use Joomla\CMS\Categories\CategoryServiceInterface;
#use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\HTML\HTMLRegistryAwareTrait;
use Psr\Container\ContainerInterface;
use Joomla\CMS\Helper\ContentHelper;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Component\Router\RouterServiceInterface;
#use NPEU\Component\Brands\Site\Service\TraditionalRouter;
use Joomla\CMS\Component\Router\RouterInterface;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Menu\AbstractMenu;
use NPEU\Component\Brands\Administrator\Service\HTML\AdministratorService;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
#use Joomla\CMS\Fields\FieldsServiceInterface;
use Joomla\Database\DatabaseAwareTrait;

class BespokeComponent extends MVCComponent implements
    RouterServiceInterface, BootableExtensionInterface
{
    use RouterServiceTrait;
    use HTMLRegistryAwareTrait;
    #use AssociationServiceTrait;
    use DatabaseAwareTrait;

    /**
     * Booting the extension. This is the function to set up the environment of the extension like
     * registering new class loaders, etc.
     *
     * We use this to register the helper file class which contains the html for displaying associations
     */
    public function boot(ContainerInterface $container)
    {
        $this->getRegistry()->register('Bespokesadministrator', new AdministratorService);
    }


    /**
     * Returns the name of the published state column in the table
     * for use by the count items function
     *
     */
    protected function getStateColumnForSection(string $section = null)
    {
        return 'state';
    }

}
