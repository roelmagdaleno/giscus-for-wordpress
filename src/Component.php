<?php

namespace Giscus;

/**
 * Parent class to render the HTML components into settings.
 *
 * @since 0.1.0
 */
abstract class Component {
	/**
	 * The component id.
	 *
	 * @since 0.1.0
	 *
	 * @var   string   $id   The component id.
	 */
	private string $id;

	/**
	 * The component section.
	 *
	 * @since 0.1.0
	 *
	 * @var   string   $section   The component section.
	 */
	public string $section;

	/**
	 * The component settings.
	 *
	 * @since 0.1.0
	 *
	 * @var   array   $settings   The component settings.
	 */
	public array $settings;

	/**
	 * Initialize the properties to render the component.
	 *
	 * @since 0.1.0
	 *
	 * @param string   $id         The component id.
	 * @param array    $settings   The component settings.
	 */
	public function __construct( string $id = '', array $settings = array() ) {
		$this->id       = $id;
		$this->settings = $settings;
	}

	/**
	 * Render the HTML component.
	 * This method must be declared in every class where this class is extended.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The HTML component.
	 */
	abstract public function render() : string;

	/**
	 * Return the HTML component when echo the instance.
	 *
	 * @since  0.1.0
	 *
	 * @return string   Return the HTML component when echo the instance.
	 */
	public function __toString() : string {
		return $this->render();
	}

	/**
	 * Render the specified attributes into the HTML component.
	 *
	 * We only accept some attributes like "onclick", "onchange", etc.
	 * Check the "$valid_attrs" variable to see which attributes are valid.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The component attributes.
	 */
	public function attributes() : string {
		if ( ! isset( $this->settings['attributes'] ) || empty( $this->settings['attributes'] ) ) {
			return '';
		}

		$attributes  = '';
		$valid_attrs = array(
			'onclick',
			'onchange',
			'oninput',
			'disabled',
			'class',
			'style',
		);

		foreach ( $this->settings['attributes'] as $attribute => $value ) {
			if ( ! in_array( $attribute, $valid_attrs, true ) && false === strpos( $attribute, 'data-' ) ) {
				continue;
			}

			if ( 'disabled' === $attribute && ! $value ) {
				continue;
			}

			$attributes .= ' ' . esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
		}

		return $attributes;
	}

	/**
	 * Get the value for the current input.
	 *
	 * If there's an "alteration" the value will be parsed according
	 * to the current value. There's only one alteration for now: "protect_string".
	 *
	 * @since  0.1.0
	 *
	 * @return mixed   The value for the current input.
	 */
	public function value() {
		$settings = get_option( $this->section, array(
			$this->id => $this->settings['default_value'] ?? false,
		) );

		return empty( $settings )
			? false
			: $settings[ $this->id ] ?? $this->settings['default_value'] ?? false;
	}

	/**
	 * Get the input id.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The input id.
	 *
	 * @SuppressWarnings(PHPMD.ShortMethodName)
	 */
	public function id() : string {
		return $this->id;
	}

	/**
	 * Get the input name.
	 * The generated input name will be constructed from the section and id.
	 *
	 * @since  0.1.0
	 *
	 * @return string   The input name.
	 */
	public function name() : string {
		return $this->section . '[' . $this->id . ']';
	}
}
