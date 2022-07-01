<?php

namespace Giscus\Components;

use Giscus\Component;

class Select extends Component {
	/**
	 * Render the HTML component.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The HTML component.
	 */
	public function render() : string {
		$description = $this->settings['description'] ?? '';

		$html  = '<select id="' . esc_attr( $this->id() ) . '" ';
		$html .= 'name="' . esc_attr( $this->name() ) . '" ' . $this->attributes() . '>';

		foreach ( $this->settings['options'] as $value => $label ) {
			$is_selected = selected( $this->value(), $value, false );
			$html       .= '<option value="' . esc_attr( $value ) . '" ' . $is_selected . ' >' . $label . '</option>';
		}

		$html .= '</select>';
		$html .= '<p class="description">' . $description . '</p>';

		return $html;
	}
}
