<?php

/*
Plugin Name:        Electro
Plugin URI:         https://www.frantic.fi/
Description:        EnerimCIS Product integration for WordPress
Version:            1.0.0
Author:             Frantic
Author URI:         https://www.frantic.fi/
*/

namespace VE\Electro;

if (! file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    return;
}

require_once $composer;

add_action('plugins_loaded', [new Plugin, 'bootstrap']);
