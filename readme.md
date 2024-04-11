# Pikari CVENT ACF Fields Plugin

A wordpress plugin that integrates CVENT data into custom ACF fields.

## Directory Structure

Directory Structure and general bas plugin setup was inspired by [mrkwp](https://www.mrkwp.com/guide/building-a-plugin-for-wordpress/reusable-blocks-menu-wpadmin/)

├ pikari-cvent-acf-fields
│ ├ core
│ │ ├ Admin
│ │ ├ Base
│ │ │ ├ BaseController.php
│ │ │ ├ Activate.php
│ │ │ ├ Deactivate.php
│ │ ├ Init.php
│ ├ lib – where composer modules are loaded
│ ├ composer.json – PSR-4
│ ├ pikari-cvent-integration.php – the main plugin file
