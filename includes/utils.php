<?php
namespace ElementorDesigner;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Utils {

	public static function get_scroll_placeholder_image_src() {
		return apply_filters( 'elementor_designer/utils/get_scroll_placeholder_image_src', ELEMENTOR_DESIGNER_ASSETS_URL . 'images/screenshot.png' );
	}
	
	public static function get_designer_placeholder_image_src() {
		return apply_filters( 'elementor_designer/utils/get_designer_placeholder_image_src', ELEMENTOR_DESIGNER_ASSETS_URL . 'images/placeholder.svg' );
	}
}