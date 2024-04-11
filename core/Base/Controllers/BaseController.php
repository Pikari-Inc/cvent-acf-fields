<?php

/**
 * The base controller
 * We use the BaseController class to set up a series of base methods.
 * These methods are plugin references for stuff such as the plugin directory path, among others.
 *
 * @package Pikari_Cvent_Fields
 */

namespace Pikari\CventAcfFields\Base\Controllers;

/**
 * The base controller
 */
class BaseController
{
    /**
     * The plugin path on the server
     *
     * @var [type]
     */
    public $plugin_path;

    /**
     * The plugin URL
     *
     * @var [type]
     */
    public $plugin_url;

    /**
     * The plugin
     *
     * @var [type]
     */
    public $plugin;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->plugin_path = plugin_dir_path(dirname(__DIR__, 1));
        $this->plugin_url = plugin_dir_url(dirname(__DIR__, 1));
        $this->plugin = plugin_basename(dirname(__DIR__, 2)) . '/pikari-cvent-acf-fields.php';
    }
}
