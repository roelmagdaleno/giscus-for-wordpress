<?php

/**
 * Plugin Name:       Giscus for WordPress
 * Plugin URI:        https://roelmagdaleno.com
 * Description:       A comments system powered by GitHub Discussions. Let visitors leave comments and reactions on your website via GitHub!
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

$giscus = new Giscus();
$giscus->hooks();

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_script(
		'giscus-for-wordpress',
		'https://giscus.app/client.js',
		null,
		'0.1.0',
		true
	);
} );

add_filter( 'script_loader_tag', function ( $tag, $handle, $src ) {
	if ( 'giscus-for-wordpress' !== $handle ) {
		return $tag;
	}

	$attributes = array(
		'data-repo'              => 'roelmagdaleno/roelmagdaleno.com',
		'data-repo-id'           => 'MDEwOlJlcG9zaXRvcnkyMjA1OTM3MzU=',
		'data-category'          => 'Announcements',
		'data-category-id'       => 'DIC_kwDODSX-R84CP3bK',
		'data-mapping'           => 'pathname',
		'data-reactions-enabled' => '1',
		'data-emit-metadata'     => '0',
		'data-input-position'    => 'bottom',
		'data-theme'             => 'light',
		'data-lang'              => 'es',
		'data-loading'           => 'lazy',
		'crossorigin'            => 'anonymous',
	);

	$tag = '<script src="' . esc_attr( $src ) . '" ';

	foreach ( $attributes as $attribute => $value ) {
		$tag .= $attribute . '=' . $value . ' ';
	}

	$tag .= 'async></script>';

	return $tag;
}, 10, 3 );
