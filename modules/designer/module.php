<?php
namespace ElementorDesigner\Modules\Designer;

use ElementorDesigner\Base\Module_Base;
use Elementor\Controls_Manager;

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		// This is here for extensibility purposes - go to town and make things happen!
	}
	
	public function get_name() {
		return 'template-designer';
	}

	public function get_widgets() {
		return [
			'Template_Preview', // What is it goes here. This should match the widget/element class - the file name should also match but in small caps!
			'Frame_It',
		];
	}
	
}