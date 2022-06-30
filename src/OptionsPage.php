<?php

namespace Giscus;

use Giscus\Components\Radio;
use Giscus\Components\Select;
use Giscus\Components\Text;

class OptionsPage {
	protected string $page = 'giscus';

	public function hooks() : void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

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

	public function register_settings() : void {
		register_setting( 'giscus_group', 'giscus_settings' );

		add_settings_section(
			$this->page,
			'Settings',
			null,
			$this->page
		);

		$settings = array(
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
				'label'       => 'Repository',
				'placeholder' => 'myusername/repo',
			) ),
			new Radio( 'mapping', array(
				'label'         => 'Page ↔️ Discussions Mapping',
				'default_value' => 'url',
				'options'       => array(
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
		);

		foreach ( $settings as $setting ) {
			$setting->section = 'giscus_settings';

			add_settings_field(
				$setting->id(),
				$setting->settings['label'],
				array( $this, 'render_field' ),
				$this->page,
				$this->page,
				array( 'instance' => $setting )
			);
		}
	}

	public function render_field( array $args = array() ) {
		if ( empty( $args ) || ! isset( $args['instance'] ) ) {
			return;
		}

		echo $args['instance']->render();
	}

	public function render() : void {
		include_once dirname( __DIR__ ) . '/admin/views/options-page.php';
	}
}
