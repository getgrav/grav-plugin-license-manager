<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Plugin\LicenseManager\LicenseManager;
use Grav\Plugin\LicenseManager\LicenseManagerController;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class LicenseManagerPlugin
 * @package Grav\Plugin
 */
class LicenseManagerPlugin extends Plugin
{
    protected $admin_route = 'license-manager';
    protected $data;

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        require_once __DIR__ . '/vendor/autoload.php';

        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            $this->enable([
                'onTwigTemplatePaths' => ['onTwigAdminTemplatePaths', 0],
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
                'onAdminMenu' => ['onAdminMenu', 0],
                'onAdminTaskExecute' => ['onAdminTaskExecute', 0],
                'onDataTypeExcludeFromDataManagerPluginHook' => ['onDataTypeExcludeFromDataManagerPluginHook', 0],
            ]);

            $this->data = LicenseManager::load();
        }

    }

    /**
     * Add plugin templates path
     */
    public function onTwigAdminTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/admin/templates';
    }

    /**
     * Add license data to Twig
     */
    public function onTwigSiteVariables()
    {
        // Twig shortcuts
        $this->grav['twig']->twig_vars['license_data'] = $this->data;
    }

    /**
     * Add License Manager to admin menu
     */
    public function onAdminMenu()
    {
        $this->grav['twig']->plugins_hooked_nav['PLUGIN_LICENSE_MANAGER.TITLE'] = ['route' => $this->admin_route, 'icon' => 'fa-key'];
    }

    public function onAdminTaskExecute(Event $event)
    {
        $controller = new LicenseManagerController($event['controller'], $event['method']);

        return $controller->execute();
    }

    /**
     * Exclude Licence Manager data from the Data Manager plugin
     */
    public function onDataTypeExcludeFromDataManagerPluginHook()
    {
        $this->grav['admin']->dataTypesExcludedFromDataManagerPlugin[] = 'licenses';

    }



}
