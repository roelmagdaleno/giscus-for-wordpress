<?php

namespace Giscus\Components;

use Giscus\Component;

class Checkbox extends Component {
	/**
	 * Render the HTML component.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The HTML component.
	 */
	public function render() : string {
		$value = $this->value();

		$html  = '<label for="' . esc_attr( $this->id() ) . '">';
		$html .= '<input type="checkbox" id="' . esc_attr( $this->id() ) . '" ';
		$html .= 'name="' . esc_attr( $this->name() ) . '" value="' . esc_attr( $value ) . '" ';
		$html .= checked( '1', $value, false ) . ' />';
		$html .= $this->settings['description'] ?? '';
		$html .= '</label>';

		return $html;
	}
}
