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
			( new OptionsPage() )->hooks();
			return;
		}

		( new Script() )->hooks();
		( new Comments() )->hooks();
	}
}
