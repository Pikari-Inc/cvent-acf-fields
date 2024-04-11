<?php

/**
 * Contract for initializing classes
 *
 * @package Pikari_Cvent_Fields
 *
 * @since 0.0.1
 */

namespace Pikari\CventAcfFields;

/**
 * The interface for for initializing classes
 */
interface InitInterface
{
    /**
     * Default register method called by the Init class
     *
     * @return void
     */
    public function register(): void;
}
