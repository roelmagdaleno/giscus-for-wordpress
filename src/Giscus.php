<?php

namespace Giscus;

class Giscus {
	/**
	 * Initialize the hooks that will run the Giscus functionality.
	 *
	 * @since 0.1.0
	 */
	public function hooks() : void {
		if ( is_admin() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			( new OptionsPage() )->hooks();

			return;
		}

		( new Script() )->hooks();
		( new Comments() )->hooks();
	}

	public function enqueue_admin_assets( string $hook_suffix ) {
		if ( 'settings_page_giscus' !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'giscus-css',
			plugins_url( 'admin/css/giscus.css', __DIR__ ),
			null,
			GISCUS_WP_VERSION
		);

		wp_enqueue_script(
			'giscus-admin',
			plugins_url( 'admin/js/giscus.js', __DIR__ ),
			null,
			GISCUS_WP_VERSION
		);
	}
}
