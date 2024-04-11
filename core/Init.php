<?php

/**
 * @package Pikari_Cvent_Fields
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields;

final class Init
{
    /**
     * Our bootstrapping method, called in the main plugin file.
     *
     * @return void
     */
    public static function boot()
    {
        self::registerACFFields();
    }

    /**
     * Loop through the classes, initialize them, and call the register() method if it exists
     *
     * @return array Full list of classes
     */
    public static function getACFFields(): array
    {
        return array(
            ACF\Fields\CventSpeakersSelect::class,
            ACF\Fields\CventSessionsSelect::class,
            ACF\Fields\CventExhibitorsSelect::class,
        );
    }

    /**
     * Loop through the classes, register them with ACF
     *
     * @return void
     */
    public static function registerACFFields()
    {
        if (! function_exists('acf_register_field_type')) {
            return;
        }

        foreach (self::getACFFields() as $class) {
            $field = self::instantiate($class);
            acf_register_field_type($field);
        }
    }

    /**
     * Initialize the class
     *
     * @param class $class class from the services array.
     * @return class instance new instance of the class
     */
    private static function instantiate($class)
    {
        $service = new $class();
        return $service;
    }
}
