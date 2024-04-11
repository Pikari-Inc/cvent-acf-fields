<?php

/**
 * The activation class, which is responsible for running code when the plugin is activated.
 *
 * @package Pikari_Cvent_Fields
 */

namespace Pikari\CventAcfFields\Base;

/**
 * Activation class
 */
class Activate
{
    /**
     * Activate the plugin
     *
     * @since 0.0.1
     *
     * @return void
     */
    public static function activate()
    {
        flush_rewrite_rules();
    }
}
