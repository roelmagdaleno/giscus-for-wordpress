<?php

namespace Giscus\Components;

use Giscus\Component;

class Text extends Component {
	/**
	 * Render the HTML component.
	 *
	 * @since  0.8.0
	 *
	 * @return string   The HTML component.
	 */
	public function render() : string {
		$description = $this->settings['description'] ?? '';

		$html  = '<input type="text" id="' . esc_attr( $this->id() ) . '" ';
		$html .= 'name="' . esc_attr( $this->name() ) . '" value="' . esc_attr( $this->value() ) . '" ';
		$html .= 'class="regular-text" ' . esc_attr( $this->placeholder() ) . '>';

		if ( isset( $this->settings['use_spinner'] ) ) {
			$html .= '<span class="spinner"></span>';
		}

		$html .= '<p class="description">' . $description . '</p>';

		return $html;
	}

	/**
	 * Get the `<input />` placeholder.
	 *
	 * @since  0.1.0
	 *
	 * @return false|string   The `<input />` placeholder.
	 */
	public function placeholder() {
		return ! isset( $this->settings['placeholder'] ) ? false : 'placeholder=myusername/myrepo ';
	}
}
