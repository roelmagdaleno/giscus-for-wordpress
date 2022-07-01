<?php

namespace Giscus;

class Script {
	/**
	 * The script handle.
	 *
	 * @since 0.1.0
	 *
	 * @var   string   $handle   The script handle.
	 */
	protected string $handle = 'giscus-for-wordpress';

	/**
	 * Initialize the hooks that will run the Giscus functionality.
	 *
	 * @since 0.1.0
	 */
	public function hooks() : void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 99 );
		add_filter( 'script_loader_tag', array( $this, 'set_attributes' ), 10, 3 );
	}

	/**
	 * Enqueue the scripts in frontend.
	 *
	 * The main script is loaded externally. Roel tried to load the script
	 * locally but got some issues when loading the Giscus app. It shouldn't affect
	 * too much the performance, though.
	 *
	 * Also, it will dequeue the "comment-reply" script because is no longer used when
	 * this plugin is enabled.
	 *
	 * @since 0.1.0
	 */
	public function enqueue_scripts() : void {
		wp_dequeue_script( 'comment-reply' );
		wp_enqueue_script(
			$this->handle,
			'https://giscus.app/client.js',
			null,
			GISCUS_WP_VERSION,
			true
		);
	}

	/**
	 * Set the dataset attributes to the loaded giscus script HTML tag.
	 * The dataset attributes are filled from the user's settings.
	 *
	 * @since  0.1.0
	 *
	 * @param  string   $tag      The script HTML tag.
	 * @param  string   $handle   The script handle.
	 * @param  string   $src      The script source.
	 * @return string             The giscus script with dataset attributes.
	 */
	public function set_attributes( string $tag, string $handle, string $src ) : string {
		if ( $this->handle !== $handle ) {
			return $tag;
		}

		$settings = get_option( 'giscus_settings', array() );

		if ( empty( $settings ) || ! isset( $settings['repository'], $settings['repositoryId'] ) ) {
			return $tag;
		}

		$attributes = array(
			'data-repo'              => $settings['repository'],
			'data-repo-id'           => $settings['repositoryId'],
			'data-category'          => $settings['categoryName'] ?? '',
			'data-category-id'       => $settings['category'] ?? '',
			'data-mapping'           => $settings['mapping'] ?? 'pathname',
			'data-reactions-enabled' => $settings['reactionsEnabled'] ?? '0',
			'data-emit-metadata'     => $settings['emitMetadata'] ?? '0',
			'data-input-position'    => isset( $settings['inputPosition'] ) ? 'top' : 'bottom',
			'data-theme'             => $settings['theme'] ?? 'light',
			'data-lang'              => $settings['language'] ?? 'en',
		);

		if ( isset( $settings['lazyLoad'] ) ) {
			$attributes['data-loading'] = 'lazy';
		}

		$tag = '<script src="' . esc_attr( $src ) . '" ';

		foreach ( $attributes as $attribute => $value ) {
			$tag .= esc_attr( $attribute ) . '="' . esc_attr( $value ) . '" ';
		}

		$tag .= 'crossorigin="anonymous" async></script>';

		return $tag;
	}
}
