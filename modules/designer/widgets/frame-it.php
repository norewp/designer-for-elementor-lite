<?php
namespace ElementorDesigner\Modules\Designer\Widgets;

// You can add to or remove from this list - it's not conclusive! Chop & change to fit your needs.
use Elementor;
use Elementor\Control_Media;
use ElementorDesigner\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Frame_It extends Widget_Base {

	/* Uncomment the line below if you do not wish to use the function _content_template() - leave that section empty if this is uncommented! */
	//protected $_has_template_content = false; 
	
	public function get_name() {
		return 'frame-it';
	}

	public function get_title() {
		return __( 'Frame It', 'elementor-designer' );
	}

	public function get_icon() {
		return 'eicon-designer-frameit';
	}

	public function get_categories() {
		return [ 'designer-elements'];
	}
	
	protected function _register_controls() {
		$args = array( 'post_type' => 'download', 'posts_per_page' =>  -1 );

		$query = new \WP_Query( $args );

		$downloads = array();
		while ( $query->have_posts() ) : $query->the_post(); 
			$downloads[ get_the_ID() ] = get_the_title();
		endwhile; 
		
		foreach ( $downloads as $download ) {
			$new_id = $download;
		}		

		wp_reset_postdata();
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content Controls', 'elementor-designer' ),
			]
		);
		
		$this->start_controls_tabs( 'framed_content' );

		$this->start_controls_tab( 'image', [ 'label' => __( 'Image', 'elementor-designer' ) ] );
		
		$this->add_control(
			'framed_image',
			[
				'label' => __( 'Choose Image', 'elementor-designer' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_designer_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'frame_alt',
			[
				'label' => __( 'Image Alt Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Awesome Template', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'frame_width',
			[
				'label' => __( 'Frame Border Width', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 5,
					'unit' => 'vmin',
				],
				'range' => [
					'vmin' => [
						'min' => 2,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'description' => __( 'Sets a frame border-width in vmin.', 'elementor-designer' ),
			]
		);
		
		$this->add_responsive_control(
			'frame_radius',
			[
				'label' => __( 'Frame Border Radius', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 25,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'description' => __( 'Sets a frame border-width in px.', 'elementor-designer' ),
			]
		);
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'frame_box_shadow',
				'selector' => '{{WRAPPER}} .picture-frame',
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'settings', [ 'label' => __( 'Settings', 'elementor-designer' ) ] );
		
		$this->add_responsive_control(
			'inner_padding',
			[
				'label' => __( 'Padding', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,	
				'size_units' => [ 'vmin', '%' ],
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'image_border',
			[
				'label' => __( 'Image Border Width', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .picture-frame img' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'description' => __( 'Sets a image border-width in px.', 'elementor-designer' ),
			]
		);
		
		$this->add_control(
			'sale_banner_on',
			[
				'label' => __( 'Show Sale Badge', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Show the sales badge over the frame?', 'elementor-designer' ),
			]
		);
		
		$this->add_responsive_control(
			'iframe_top_pad',
			[
				'label' => __( 'Preview Top Offset', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 35,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .modal__content iframe' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
				'description' => __( 'Adjust the preview content\'s top offset.', 'elementor-designer' ),
			]
		);
		
		$this->add_responsive_control(
			'iframe_bottom_pad',
			[
				'label' => __( 'Preview Bottom Offset', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 65,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .modal__content iframe' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
				'description' => __( 'Adjust the preview content\'s bottom offset.', 'elementor-designer' ),
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor-designer' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_ribbon',
			[
				'label' => __( 'Badge Controls', 'elementor-designer' ),
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->start_controls_tabs( 'badge_tabs' );

		$this->start_controls_tab( 'badge', [ 'label' => __( 'Badge', 'elementor-designer' ) ] );
		
		$this->add_control(
			'sale_text',
			[
				'label' => __( 'Sale Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'SAVE', 'elementor-designer' ),
				'separator' => 'before',
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'sale_discount',
			[
				'label' => __( 'Discount % or $', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '10%', 'elementor-designer' ),
				'separator' => 'before',
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'banner_padding',
			[
				'label' => __( 'Padding', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,	
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'sale_width',
			[
				'label' => __( 'Badge Width', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 90,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 45,
						'max' => 150,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'sale_height',
			[
				'label' => __( 'Badge Height', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 24,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'pointer', [ 'label' => __( 'Pointer', 'elementor-designer' ) ] );
		
		$this->add_responsive_control(
			'pointer_v_pos',
			[
				'label' => __( 'Pointer Gap', 'elementor-designer' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 60,
					'unit' => 'px',
				],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon:after' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'badge_misc', [ 'label' => __( 'Misc', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'badge_box_shadow',
				'selector' => '{{WRAPPER}} .framed-ribbon',
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sale_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .framed-ribbon',
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cta',
			[
				'label' => __( 'Call To Action Controls', 'elementor-designer' ),
			]
		);
		
		$this->start_controls_tabs( 'cta_main_tabs' );

		$this->start_controls_tab( 'buy_cta', [ 'label' => __( 'Buy Button', 'extend-elements' ) ] );
		
		$this->add_control(
			'buy_text',
			[
				'label' => __( 'Buy Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Buy Now', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'edd_product_on',
			[
				'label' => __( 'Link to EDD Product?', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Show the sales badge over the frame?', 'elementor-designer' ),
			]
		);
		
		$this->add_control(
			'download',
			[
				'label' => __( 'Product', 'elementor-woostore' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $downloads,
				'condition' => [
					'edd_product_on' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'buy_link',
			[
				'label' => __( 'Buy Link', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'This is the link of the template/website you wish to be previewed.', 'elementor-designer' ),
				'placeholder' => __( 'http://your-link.com', 'elementor-designer' ),
				'separator' => 'before',
				'condition' => [
					'edd_product_on' => '',
				],
			]
		);
		
		$this->add_responsive_control(
			'buy_padding',
			[
				'label' => __( 'Padding', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,	
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .buy__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'buy_border',
				'label' => __( 'Border', 'extend-elements' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .buy__trigger',
			]
		);
		
		$this->add_control(
			'buy_radius',
			[
				'label' => __( 'Border Radius', 'extend-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .buy__trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'buy_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .buy__trigger',
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'preview_cta', [ 'label' => __( 'Preview Button', 'extend-elements' ) ] );
		
		$this->add_control(
			'preview_text',
			[
				'label' => __( 'Preview Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Preview', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'preview_link',
			[
				'label' => __( 'Preview Link', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'description' => __( 'This is the link of the template/website you wish to be previewed.', 'elementor-designer' ),
				'default' => __( 'https://library.elementor.com/homepage-goodness-meal-services/', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_responsive_control(
			'preview_padding',
			[
				'label' => __( 'Padding', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,	
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .modal__trigger' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'preview_border',
				'label' => __( 'Border', 'extend-elements' ),
				'placeholder' => '1px',
				'default' => '',
				'selector' => '{{WRAPPER}} .modal__trigger',
			]
		);
		
		$this->add_control(
			'preview_radius',
			[
				'label' => __( 'Border Radius', 'extend-elements' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .modal__trigger' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'preview_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .modal__trigger',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'preview_bars',
			[
				'label' => __( 'Preview Bars', 'elementor-designer' ),
			]
		);
				
		$this->start_controls_tabs( 'Preview_bar_tabs' );

		$this->start_controls_tab( 'top_bar', [ 'label' => __( 'Top Bar', 'elementor-designer' ) ] );
		
		$this->add_control(
			'top_bar_on',
			[
				'label' => __( 'Enable Top Bar', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Select weather to show or hide the top preview bar!', 'elementor-designer' ),
			]
		);
		
		$this->add_control(
			'template_title',
			[
				'label' => __( 'Product Title', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Awesome Template', 'elementor-designer' ),
				'separator' => 'before',
				'condition' => [
					'top_bar_on' => 'yes',
				],
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'template_title_typography',
				'label' => __( 'Typography', 'elementor-designer' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .modal .top-panel .template-name',
				'condition' => [
					'top_bar_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'bottom_bar', [ 'label' => __( 'Bottom Bar', 'elementor-designer' ) ] );
		
		$this->add_control(
			'bottom_bar_on',
			[
				'label' => __( 'Enable Bottom Bar', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'yes',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Select weather to show or hide the bottom preview bar!', 'elementor-designer' ),
			]
		);
		
		$this->add_control(
			'template_desc',
			[
				'label' => __( 'Product Description', 'elementor-designer' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'A brief description', 'elementor-designer' ),
				'description' => __( 'The description will be shown in the left pane of the footer panel.', 'elementor-designer' ),
				'separator' => 'before',
				'condition' => [
					'bottom_bar_on' => 'yes',
				],
			]
		);
		
		$this->add_responsive_control(
			'description_padding',
			[
				'label' => __( 'Padding', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,	
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .modal .bottom-panel .template-details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'bottom_bar_on' => 'yes',
				],
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'template_desc_typography',
				'label' => __( 'Typography', 'elementor-designer' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .modal .bottom-panel .template-details',
				'condition' => [
					'bottom_bar_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'frame_style',
			[
				'label' => __( 'Frame', 'elementor-designer' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'framed_styles' );

		$this->start_controls_tab( 'frame_outer', [ 'label' => __( 'Frame', 'elementor-designer' ) ] );
		
		$this->add_control(
			'frame_top_border',
			[
				'label' => __( 'Frame Top Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#af2b2b',
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'frame_right_border',
			[
				'label' => __( 'Frame Right Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a52a2a',
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'frame_bottom_border',
			[
				'label' => __( 'Frame Bottom Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#af2b2b',
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'frame_left_border',
			[
				'label' => __( 'Frame Left Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#a52a2a',
				'selectors' => [
					'{{WRAPPER}} .picture-frame' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'frame_inner', [ 'label' => __( 'Image', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Inner Background', 'elementor-designer' ),
				'name' => 'inner_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .picture-frame',
				'default' => '#ddddcc',
			]
		);
		
		$this->add_control(
			'inner_top_border',
			[
				'label' => __( 'Image Top Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ccccbb',
				'selectors' => [
					'{{WRAPPER}} .picture-frame img' => 'border-top-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'inner_right_border',
			[
				'label' => __( 'Image Right Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#eeeedd',
				'selectors' => [
					'{{WRAPPER}} .picture-frame img' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'inner_bottom_border',
			[
				'label' => __( 'Image Bottom Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffee',
				'selectors' => [
					'{{WRAPPER}} .picture-frame img' => 'border-bottom-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'inner_left_border',
			[
				'label' => __( 'Image Left Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#eeeedd',
				'selectors' => [
					'{{WRAPPER}} .picture-frame img' => 'border-left-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'badge_styles',
			[
				'label' => __( 'Badge Styles', 'elementor-designer' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'sale_banner_on' => 'yes',
				],
			]
		);
		
		$this->start_controls_tabs( 'badge_tabs_name' );

		$this->start_controls_tab( 'badge_tab_one', [ 'label' => __( 'Badge', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Badge Background', 'elementor-designer' ),
				'name' => 'badge_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .framed-ribbon',
				'default' => '#d3362d',
			]
		);
		
		$this->add_control(
			'badge_color',
			[
				'label' => __( 'Badge Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'Badge_tab_two', [ 'label' => __( 'Pointer', 'elementor-designer' ) ] );
		
		$this->add_control(
			'pointer_color',
			[
				'label' => __( 'Pointer Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#e57368',
				'selectors' => [
					'{{WRAPPER}} .framed-ribbon:after' => 'border-top-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'buttons_style',
			[
				'label' => __( 'Buttons', 'elementor-designer' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'buy_tabs' );
		
		$this->start_controls_tab( 'buy_normal', [ 'label' => __( 'Buy Colors', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Buy Background', 'elementor-designer' ),
				'name' => 'buy_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .buy__trigger',
				'default' => '#ffebee',
			]
		);
		
		$this->add_control(
			'buy_color',
			[
				'label' => __( 'Buy Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.7)',
				'selectors' => [
					'{{WRAPPER}} .buy__trigger' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'buy_hover', [ 'label' => __( 'Buy Hover', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Buy Hover BG', 'elementor-designer' ),
				'name' => 'buy_hover_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .buy__trigger:hover',
				'default' => '#f44336',
			]
		);
		
		$this->add_control(
			'buy_hover_color',
			[
				'label' => __( 'Buy Hover Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .buy__trigger:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->start_controls_tabs( 'preview_tabs' );
		
		$this->start_controls_tab( 'preview_normal', [ 'label' => __( 'Preview Normal', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Preview BG', 'elementor-designer' ),
				'name' => 'preview_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .modal__trigger',
				'default' => '#ffebee',
			]
		);
		
		$this->add_control(
			'preview_color',
			[
				'label' => __( 'Preview Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.7)',
				'selectors' => [
					'{{WRAPPER}} .modal__trigger' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'preview_hover', [ 'label' => __( 'Preview Hover', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Preview Hover BG', 'elementor-designer' ),
				'name' => 'preview_hover_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .modal__trigger:hover',
				'default' => '#f44336',
			]
		);
		
		$this->add_control(
			'preview_hover_color',
			[
				'label' => __( 'Preview Hover Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .modal__trigger:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'bar_styles',
			[
				'label' => __( 'Preview Bars', 'elementor-designer' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'bar_style_tabs' );

		$this->start_controls_tab( 'top_bar_style', [ 'label' => __( 'Top Bar Styles', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Top Bar BG', 'elementor-designer' ),
				'name' => 'top_bar_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .modal .top-panel',
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'top_bar_color',
			[
				'label' => __( 'Name Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#393939',
				'selectors' => [
					'{{WRAPPER}} .modal .top-panel .template-name' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'modal_close_bg',
			[
				'label' => __( 'Close BG', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(0,0,0,0.6)',
				'selectors' => [
					'{{WRAPPER}} .modal-close' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'modal_close_color',
			[
				'label' => __( 'Close Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .modal-close svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'bottom_bar_style', [ 'label' => __( 'Bottom Bar Styles', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Bottom Bar BG', 'elementor-designer' ),
				'name' => 'bottom_bar_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .modal .bottom-panel',
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'bottom_bar_color',
			[
				'label' => __( 'Description Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#393939',
				'selectors' => [
					'{{WRAPPER}} .modal .bottom-panel .template-details' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
	}
		
	protected function render() {
		$settings 	= $this->get_settings();
		$title 		= $settings['template_title'];
		$item_desc	= $settings['template_desc'];
		$alt		= $settings['frame_alt'];
		$buy_url	= $settings['buy_link'];
		$prev_url	= $settings['preview_link'];
		$sale_on 	= $settings['sale_banner_on'];
		$disc_text 	= $settings['sale_text'];
		$amount 	= $settings['sale_discount'];
		
		$id 		= $settings['download'];
		$prod_on 	= $settings['edd_product_on'];
		
		$topbar_on 	= $settings['top_bar_on'];
		$botbar_on 	= $settings['bottom_bar_on'];
		?>
		
		<div class="picture-frame">
			<?php if ( $sale_on ) { ?>
				<div class="framed-ribbon"><?php echo $disc_text; ?> <?php echo $amount; ?></div>
			<?php } ?>
			<img src="<?php echo $settings['framed_image']['url']; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" >
		</div>
		
		<div class="framed modal-btns">
			<div class="info">
				<div class="buttons">
					<?php if ( $prod_on ) { ?>
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>checkout?edd_action=add_to_cart&download_id=<?php echo $id; ?>"><span class="buy-text"><?php echo $settings['buy_text']; ?></span></a>
					<?php } else { ?>
						<a href="<?php echo $buy_url; ?>" class="buy__trigger"><?php echo $settings['buy_text']; ?></a>
					<?php } ?>
					<a href="<?php echo $prev_url; ?>" data-modal="#modal-<?php echo $this->get_id(); ?>" class="modal__trigger"><?php echo $settings['preview_text']; ?></a>
				</div>
			</div>
		</div>

		<!-- Modal -->
		<div id="modal-<?php echo $this->get_id(); ?>" class="modal modal--align-top modal__bg framed" role="dialog" aria-hidden="true">
			<?php if ( $topbar_on ) { ?>
			<div class="top-panel">
				<div class="left-pane">
					<h3 class="template-name"><?php echo $title; ?></h3>
				</div>
				<div class="right-pane">
					
				</div>
			</div>
			<?php } ?>
			<!-- modal close button -->
			<a href="" class="modal__close modal-close">
				<svg class="" viewBox="0 0 24 24">
					<path d="M19 6.41l-1.41-1.41-5.59 5.59-5.59-5.59-1.41 1.41 5.59 5.59-5.59 5.59 1.41 1.41 5.59-5.59 5.59 5.59 1.41-1.41-5.59-5.59z"/>
					<path d="M0 0h24v24h-24z" fill="none"/>
				</svg>
			</a>
			<div class="modal__dialog">
				<div class="modal__content">
					<iframe src="<?php echo $prev_url; ?>" seamless="" width="100%" height="100%" frameborder="0"></iframe>
					<!-- close button original location -->
				</div>
			</div>
			<?php if ( $botbar_on ) { ?>
			<div class="bottom-panel">
				<div class="left-pane">
					<div class="template-details"><?php echo $item_desc; ?></div>
				</div>
				<div class="right-pane">					
					<div class="framed modal-btns">
						<div class="info">
							<div class="buttons">
								<?php if ( $prod_on ) { ?>
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>checkout?edd_action=add_to_cart&download_id=<?php echo $id; ?>"><span class="buy-text"><?php echo $settings['buy_text']; ?></span></a>
								<?php } else { ?>
									<a href="<?php echo $buy_url; ?>" class="buy__trigger"><?php echo $settings['buy_text']; ?></a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
		
	<?php
	}

	protected function _content_template() {}
	
}