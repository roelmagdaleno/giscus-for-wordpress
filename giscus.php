<?php

/**
 * Plugin Name:       Giscus for WordPress
 * Plugin URI:        https://roelmagdaleno.com
 * Description:       Use Giscus in WordPress as a comments system powered by GitHub Discussions.
 * Version:           0.1.0
 * Requires at least: 5.0
 * Requires PHP:      7.0
 * Author:            Roel Magdaleno
 * Author URI:        https://roelmagdaleno.com
 */

use Giscus\Giscus;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/constants.php';

( new Giscus() )->hooks();
