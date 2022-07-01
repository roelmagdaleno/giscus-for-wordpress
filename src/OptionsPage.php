<?php

namespace Giscus;

use Giscus\Components\{
	Checkbox,
	Hidden,
	Radio,
	Select,
	Text,
};

class OptionsPage {
	/**
	 * The page slug.
	 *
	 * @since 0.1.0
	 *
	 * @var   string   $page   The page slug.
	 */
	protected string $page = 'giscus';

	/**
	 * Initialize the hooks that will run the Giscus functionality.
	 *
	 * @since 0.1.0
	 */
	public function hooks() : void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register the submenu page.
	 * The submenu page will include the plugin's options page.
	 *
	 * @since 0.1.0
	 */
	public function register_menu() : void {
		add_submenu_page(
			'options-general.php',
			'Giscus',
			'Giscus',
			'manage_options',
			$this->page,
			array( $this, 'render' )
		);
	}

	/**
	 * Register the settings to render in the options page.
	 * Each setting must be a component instance.
	 *
	 * @since 0.1.0
	 */
	public function register_settings() : void {
		register_setting( 'giscus_group', 'giscus_settings' );

		add_settings_section(
			$this->page,
			'Settings',
			null,
			$this->page
		);

		$settings = array(
			new Hidden( 'repositoryId' ),
			new Hidden( 'categoryName' ),
			new Hidden( 'categories' ),
			new Select( 'language', array(
				'label'         => 'Language',
				'default_value' => 'en',
				'options'       => array(
					'de'    => 'Deutsch',
					'gsw'   => 'Deutsch (Schweiz)',
					'en'    => 'English',
					'es'    => 'Español',
					'fr'    => 'Français',
					'id'    => 'Indonesia',
					'it'    => 'Italiano',
					'ja'    => '日本語',
					'ko'    => '한국어',
					'pl'    => 'Polski',
					'ro'    => 'Română',
					'ru'    => 'Русский',
					'tr'    => 'Türkçe',
					'vi'    => 'Việt Nam',
					'zh-CN' => '简体中文',
					'zh-TW' => '繁體中文',
				),
			) ),
			new Text( 'repository', array(
				'label'       => 'GitHub Repository',
				'placeholder' => 'myusername/repo',
				'use_spinner' => true,
				'description' => 'A public GitHub repository. This repo is where the discussions will be linked to.',
			) ),
			new Radio( 'mapping', array(
				'label'   => 'Page ↔️ Discussions Mapping',
				'options' => array(
					'pathname' => array(
						'label'       => 'Discussion title contains page <code>pathname</code>',
						'description' => 'giscus will search for a discussion whose title contains the page\'s <code>pathname</code> URL component.',
					),
					'url'      => array(
						'label'       => 'Discussion title contains page <code>URL</code>',
						'description' => 'giscus will search for a discussion whose title contains the page\'s URL.',
					),
					'title'    => array(
						'label'       => 'Discussion title contains page <code>&lt;title&gt;</code>',
						'description' => 'giscus will search for a discussion whose title contains the page\'s <code>&lt;title&gt;</code> HTML tag.',
					),
					'og:title' => array(
						'label'       => 'Discussion title contains page <code>og:title</code>',
						'description' => 'giscus will search for a discussion whose title contains the page\'s  <a href="https://ogp.me" target="_blank" rel="noreferrer noopener nofollow"><code>&lt;meta property="og:title"&gt;</code></a> HTML tag.',
					),
					'specific' => array(
						'label'       => 'Discussion title contains a specific term',
						'description' => 'giscus will search for a discussion whose title contains a specific term.',
					),
					'number'   => array(
						'label'       => 'Specific discussion number',
						'description' => 'giscus will load a specific discussion by number. This option <strong>does not</strong> support automatic discussion creation.',
					),
				),
			) ),
			new Select( 'category', array(
				'label'       => 'Discussion Category',
				'description' => 'Choose the discussion category where new discussions will be created. It is recommended to use a category with the <strong>Announcements</strong> type so that new discussions can only be created by maintainers and giscus. <strong>Populated after type the GitHub repository</strong>.',
				'options'     => $this->categories(),
			) ),
			new Checkbox( 'useCategory', array(
				'label'       => 'Only search for discussions in this category',
				'description' => 'When searching for a matching discussion, giscus will only search in the selected category.'
			) ),
			new Checkbox( 'reactionsEnabled', array(
				'label'       => 'Enable reactions for the main post',
				'description' => 'The reactions for the discussion\'s main post will be shown before the comments.'
			) ),
			new Checkbox( 'emitMetadata', array(
				'label'       => 'Emit discussion metadata',
				'description' => 'Discussion metadata will be sent periodically to the parent window (the embedding page).'
			) ),
			new Checkbox( 'inputPosition', array(
				'label'       => 'Place the comment box above the comments',
				'description' => 'The comment input box will be placed above the comments, so that users can leave a comment without scrolling to the bottom of the discussion.'
			) ),
			new Checkbox( 'lazyLoad', array(
				'label'       => 'Load the comments lazily',
				'description' => 'Loading of the comments will be deferred until the user scrolls near the comments container. This is done by adding <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Element/iframe#attr-loading" target="_blank" rel="noreferrer noopener nofollow"><code>loading="lazy"</code></a> to the <code>&lt;iframe&gt;</code> element.'
			) ),
			new Select( 'theme', array(
				'label'         => 'Theme',
				'default_value' => 'light',
				'options'       => array(
					'light'                  => 'GitHub Light',
					'light_high_contrast'    => 'GitHub Light High Contrast',
					'light_protanopia'       => 'GitHub Light Protanopia & Deuteranopia',
					'light_tritanopia'       => 'GitHub Light Tritanopia',
					'dark'                   => 'GitHub Dark',
					'dark_high_contrast'     => 'GitHub Dark High Contrast',
					'dark_protanopia'        => 'GitHub Dark Protanopia & Deuteranopia',
					'dark_tritanopia'        => 'GitHub Dark Tritanopia',
					'dark_dimmed'            => 'GitHub Dark Dimmed',
					'transparent_dark'       => 'Transparent Dark',
					'preferred_color_scheme' => 'Preferred color scheme',
				),
			) ),
		);

		foreach ( $settings as $setting ) {
			$setting->section = 'giscus_settings';

			$class = 'Giscus\Components\Hidden' === get_class( $setting ) ? 'gs-input-hidden' : '';

			add_settings_field(
				$setting->id(),
				$setting->settings['label'] ?? '',
				array( $this, 'render_field' ),
				$this->page,
				$this->page,
				array(
					'class'    => $class,
					'instance' => $setting,
				)
			);
		}
	}

	/**
	 * Render the setting field.
	 * The setting field must be a component instance.
	 *
	 * @since 0.1.0
	 *
	 * @param array   $args   The settings field arguments.
	 */
	public function render_field( array $args = array() ) {
		if ( empty( $args ) || ! isset( $args['instance'] ) ) {
			return;
		}

		echo $args['instance']->render();
	}

	/**
	 * Render the plugin's options page.
	 * It contains all plugin's settings.
	 *
	 * @since 0.1.0
	 */
	public function render() : void {
		include_once dirname( __DIR__ ) . '/admin/views/options-page.php';
	}

	/**
	 * Get the GitHub Discussions categories.
	 *
	 * @since  0.1.0
	 *
	 * @return array   The GitHub Discussions categories.
	 */
	public function categories() : array {
		$settings = get_option( 'giscus_settings', array() );

		$categories = array();

		if ( empty( $settings ) || ! isset( $settings['categories'] ) ) {
			return $categories;
		}

		$stored_categories = json_decode( $settings['categories'], true );

		foreach ( $stored_categories as $category ) {
			$categories[ $category['id'] ] = $category['name'];
		}

		return $categories;
	}
}
