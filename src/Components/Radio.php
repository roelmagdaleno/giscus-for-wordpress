<?php

namespace Giscus\Components;

use Giscus\Component;

class Radio extends Component {
	/**
	 * Render the HTML component.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The HTML component.
	 */
	public function render() : string {
		$html = '<fieldset id="' . esc_attr( $this->id() ) . '">';

		foreach ( $this->settings['options'] as $value => $option ) {
			$html .= '<div class="radio-wrapper">';
			$html .= '<input type="radio" id="' . esc_attr( $value ) . '" ';
			$html .= 'name="' . esc_attr( $this->name() ) . '" value="' . esc_attr( $value ) . '" ';
			$html .= checked( $value, $this->value(), false ) . '/> <label for="' . esc_attr( $value ) . '">';
			$html .= '<strong>' . $option['label'] . '</strong>';
			$html .= '<p class="description">' . $option['description'] . '</p>';
			$html .= '</label> </div>';
		}

		$html .= '</fieldset';

		return $html;
	}
}
