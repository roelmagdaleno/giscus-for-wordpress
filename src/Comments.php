<?php

namespace Giscus;

class Comments {
	/**
	 * Initialize the hooks that will run the Giscus functionality.
	 *
	 * @since 0.1.0
	 */
	public function hooks() : void {
		add_filter( 'comments_template', array( $this, 'template' ) );
	}

	/**
	 * Render the giscus template.
	 * It will override the default WordPress comments template.
	 *
	 * Before render the template, decide whether the current post will use
	 * giscus or not. This works by enabling the giscus usage for posts, pages, etc.
	 *
	 * @since  0.1.0
	 *
	 * @param  string   $theme_template   The path to the theme template file.
	 * @return string                     The path to the theme template file.
	 */
	public function template( string $theme_template ) : string {
		return dirname( __DIR__ ) . '/public/views/template.php';
	}
}
