<?php

/*
Plugin Name:        Electro
Plugin URI:         https://www.frantic.fi/
Description:        EnerimCIS Product integration for WordPress
Version:            0.0.1
Author:             Frantic
Author URI:         https://www.frantic.fi/
*/

namespace VE\Electro;

require_once __DIR__ . '/autoload.php';

require_once __DIR__ . '/src/helpers.php';

add_action('plugins_loaded', [__NAMESPACE__ . '\\Plugin', 'boot']);
