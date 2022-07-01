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
			$file = dirname( __DIR__ ) . '/giscus.php';

			register_activation_hook( $file, array( $this, 'default_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
			( new OptionsPage() )->hooks();

			return;
		}

		( new Script() )->hooks();
		( new Comments() )->hooks();
	}

	/**
	 * Create the default plugin settings if "giscus_settings" option doesn't exist.
	 * Attach this method inside the "register_activation_hook" function.
	 *
	 * @since 0.1.0
	 */
	public function default_settings() : void {
		$settings = get_option( 'giscus_settings', array() );

		if ( ! empty( $settings ) ) {
			return;
		}

		$default_settings = array(
			'language'         => 'en',
			'lazyLoad'         => '1',
			'mapping'          => 'pathname',
			'reactionsEnabled' => '1',
			'theme'            => 'light',
			'useCategory'      => '1',
		);

		update_option( 'giscus_settings', $default_settings, false );
	}

	/**
	 * Enqueue admin assets.
	 * Only load it in Giscus admin page.
	 *
	 * @since 0.1.0
	 *
	 * @param string   $hook_suffix   The hook suffix.
	 */
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
