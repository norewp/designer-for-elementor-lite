<?php
namespace ElementorDesigner;

use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	public $modules_manager;

	/**
	 * @deprecated
	 *
	 * @return string
	 */
	public function get_version() {
		return ELEMENTOR_DESIGNER_VERSION;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-designer' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'elementor-designer' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */

	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function includes() {
		require ELEMENTOR_DESIGNER_PATH . 'includes/modules-manager.php';
		require ELEMENTOR_DESIGNER_PATH . 'includes/utils.php';
	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$has_class_alias = isset( $this->classes_aliases[ $class ] );

		// Backward Compatibility: Save old class name for set an alias after the new class is loaded
		if ( $has_class_alias ) {
			$class_alias_name = $this->classes_aliases[ $class ];
			$class_to_load = $class_alias_name;
		} else {
			$class_to_load = $class;
		}

		if ( ! class_exists( $class_to_load ) ) {
			$filename = strtolower(
				preg_replace(
					[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
					[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
					$class_to_load
				)
			);
			$filename = ELEMENTOR_DESIGNER_PATH . $filename . '.php';

			if ( is_readable( $filename ) ) {
				include( $filename );
			}
		}

		if ( $has_class_alias ) {
			class_alias( $class_alias_name, $class );
		}
	}

	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_enqueue_style(
			'elementor-designer',
			ELEMENTOR_DESIGNER_ASSETS_URL . 'css/frontend' . $direction_suffix . $suffix . '.css',
			[],
			ELEMENTOR_DESIGNER_VERSION
		);
		
		wp_enqueue_style(
			'animate',
			ELEMENTOR_DESIGNER_ASSETS_URL . 'css/animate.css',
			[],
			ELEMENTOR_DESIGNER_VERSION
		);
	}

	public function enqueue_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	public function enqueue_editor_scripts() {
		$suffix = Utils::is_script_debug() ? '' : '.min';
	}

	public function register_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script(
			'designer-isotope-js',
			ELEMENTOR_DESIGNER_URL . 'assets/js/isotope.pkgd' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			false
		);
		
		wp_enqueue_script(
			'elementor-designer-js',
			ELEMENTOR_DESIGNER_URL . 'assets/js/frontend' . $suffix . '.js',
			[
				'jquery',
			],
			Plugin::instance()->get_version(),
			true
		);

	}

	public function enqueue_editor_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	}

	public function elementor_init() {
		$this->modules_manager = new Manager();

		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category(
			'designer-elements',
			[
				'title' => __( 'Designer Elements', 'elementor-designer' ),
				'icon' => 'font',
			],
			1
		);

		do_action( 'elementor_designer/init' );
	}

	private function setup_hooks() {
		add_action( 'elementor/init', [ $this, 'elementor_init' ] );

		add_action( 'elementor/frontend/before_register_scripts', [ $this, 'register_frontend_scripts' ] );

		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );

		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
	}

	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->includes();

		$this->setup_hooks();
	}
}

if ( ! defined( 'ELEMENTOR_DESIGNER_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}
