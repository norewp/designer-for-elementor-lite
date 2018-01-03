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

class Template_Preview extends Widget_Base {

	protected $_has_template_content = false; 
	
	public function get_name() {
		return 'template-preview';
	}

	public function get_title() {
		return __( 'Template Preview', 'elementor-designer' );
	}

	public function get_icon() {
		return 'eicon-designer-preview';
	}

	public function get_categories() {
		return [ 'designer-elements'];
	}
	
	protected function _register_controls() {
		$args = array( 'post_type' => 'download', 'posts_per_page' =>  -1 );

		$query = new \WP_Query( $args );

		$products = array();
		while ( $query->have_posts() ) : $query->the_post(); 
			$products[ get_the_ID() ] = get_the_title();
		endwhile; 
		
		foreach ( $products as $product ) {
			$new_id = $product;
		}		

		wp_reset_postdata();
		
		$this->start_controls_section(
			'preview_content',
			[
				'label' => __( 'Content', 'elementor-designer' ),
			]
		);
		
		$this->start_controls_tabs( 'preview_contents' );

		$this->start_controls_tab( 'content_image', [ 'label' => __( 'Image Content', 'elementor-designer' ) ] );
		
		$this->add_control(
			'preview_image',
			[
				'label' => __( 'Choose Image', 'elementor-designer' ),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_designer_placeholder_image_src(),
				],
			]
		);
		
		$this->add_control(
			'preview_alt',
			[
				'label' => __( 'Image Alt Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Awesome Template', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'preview_text',
			[
				'label' => __( 'Preview Button Text', 'elementor-designer' ),
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
				'placeholder' => __( 'http://your-link.com', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'buy_text',
			[
				'label' => __( 'Buy Button Text', 'elementor-designer' ),
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
				'options' => $products,
				'condition' => [
					'edd_product_on' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'checkout_redirect_on',
			[
				'label' => __( 'Redirect to checkout?', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'edd_product_on' => 'yes',
				],
				'default' => '',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Do you want to redirect the buy to the checkout page when product is added to cart?', 'elementor-designer' ),
			]
		);
		
		$this->add_responsive_control(
			'display_price',
			[
				'label' => __( 'Show/Hide Price', 'elementor-designer' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					'edd_product_on' => 'yes',
					'checkout_redirect_on' => '',
				],
				'default' => 1,
				'options' => [
					1 => __( 'Show', 'elementor-designer' ),
					0 => __( 'Hide', 'elementor-designer' ),
				],
				'description' => __( 'Choose to show or hide the product price on the purchase button.', 'elementor-designer' ),
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
		
		$this->end_controls_tab();

		$this->start_controls_tab( 'misc', [ 'label' => __( 'Settings', 'elementor-designer' ) ] );
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} .templates-library .previewer img',
			]
		);

		$this->add_control(
			'image_radius',
			[
				'label' => __( 'Border Radius', 'elementor-designer' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .templates-library .previewer img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'badge_on',
			[
				'label' => __( 'Show Badge', 'elementor-designer' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'YES',
				'label_off' => 'NO',
				'return_value' => 'yes',
				'description' => __( 'Show a badge over the thumbnail?', 'elementor-designer' ),
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
			'section_badge',
			[
				'label' => __( 'Badge Controls', 'elementor-designer' ),
				'condition' => [
					'badge_on' => 'yes',
				],
			]
		);
		
		$this->add_control(
			'badge_text',
			[
				'label' => __( 'Badge Text', 'elementor-designer' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New', 'elementor-designer' ),
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'badge_position',
			[
				'label' => __( 'Position', 'elementor-designer' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor-designer' ),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __( 'Right', 'elementor-designer' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'badge_typography',
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .templates-library-badge',
			]
		);
		
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
				'label' => __( 'Template Name', 'elementor-designer' ),
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
			'template_description',
			[
				'label' => __( 'Template Name', 'elementor-designer' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => __( 'Awesome Template', 'elementor-designer' ),
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
			'section_style',
			[
				'label' => __( 'Styles', 'elementor-designer' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->start_controls_tabs( 'preview_style_tabs' );

		$this->start_controls_tab( 'preview_title_styles', [ 'label' => __( 'Title', 'elementor-designer' ) ] );
		
		$this->add_control(
			'template_title_color',
			[
				'label' => __( 'Title Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#333333',
				'selectors' => [
					'{{WRAPPER}} .template-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab( 'preview_badge_styles', [ 'label' => __( 'Badge', 'elementor-designer' ) ] );
		
		$this->add_control(
			'badge_text_color',
			[
				'label' => __( 'Text Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .templates-library .banner__left' => 'color: {{VALUE}};',
					'{{WRAPPER}} .templates-library .banner__right' => 'color: {{VALUE}};',
				],
				'condition' => [
					'badge_on' => 'yes',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Background', 'elementor-designer' ),
				'name' => 'badge_left_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .templates-library .banner__left',
				'default' => '#76ab1e',
				'condition' => [
					'badge_on' => 'yes',
					'badge_position' => 'left',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'label' => __( 'Background', 'elementor-designer' ),
				'name' => 'badge_right_bg',
				'types' => [ 'none', 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .templates-library .banner__right',
				'default' => '#76ab1e',
				'condition' => [
					'badge_on' => 'yes',
					'badge_position' => 'right',
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
				'selector' => '{{WRAPPER}} .templates-library .modal-btns a .buy-text',
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'buy_color',
			[
				'label' => __( 'Buy Text Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#393939',
				'selectors' => [
					'{{WRAPPER}} .templates-library .modal-btns a .buy-text' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .templates-library .modal-btns a:hover .buy-text',
				'default' => '#131313',
			]
		);
		
		$this->add_control(
			'buy_hover_color',
			[
				'label' => __( 'Buy Hover Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .templates-library .modal-btns a:hover .buy-text' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .templates-library .modal-btns a .preview-text',
				'default' => '#ffffff',
			]
		);
		
		$this->add_control(
			'preview_color',
			[
				'label' => __( 'Preview Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .templates-library .modal-btns a .preview-text' => 'color: {{VALUE}};',
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
				'selector' => '{{WRAPPER}} .templates-library .modal-btns a:hover .preview-text',
				'default' => '#131313',
			]
		);
		
		$this->add_control(
			'preview_hover_color',
			[
				'label' => __( 'Preview Hover Color', 'elementor-designer' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .templates-library .modal-btns a:hover .preview-text' => 'color: {{VALUE}};',
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
				'condition' => [
					'top_bar_on' => 'yes',
				],
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
				'condition' => [
					'top_bar_on' => 'yes',
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
				'condition' => [
					'top_bar_on' => 'yes',
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
				'condition' => [
					'top_bar_on' => 'yes',
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
				'condition' => [
					'bottom_bar_on' => 'yes',
				],
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
				'condition' => [
					'bottom_bar_on' => 'yes',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->end_controls_section();
		
	}
		
	protected function render() {
		$settings 	= $this->get_settings(); 
		$title 		= $settings['preview_title'];
		$alt		= $settings['preview_alt'];
		$prev_url	= $settings['preview_link'];
		$buy_text	= $settings['buy_text'];
		$buy_url	= $settings['buy_link'];
		$badge_on 	= $settings['badge_on'];
		$badge_pos	= $settings['badge_position'];
		$badge_text	= $settings['badge_text'];
		
		$prod_on 	= $settings['edd_product_on'];
		
		$topbar_on 	= $settings['top_bar_on'];
		$botbar_on 	= $settings['bottom_bar_on'];
		
		$temp_name	= $settings['template_title'];
		$item_desc	= $settings['template_description'];
		?>
		
		<div class="templates-library">	
		<?php if ( $badge_on ) { ?>
			<div class="banner-container__<?php echo $badge_pos; ?>">
				<div class="banner__<?php echo $badge_pos; ?>"><span class="templates-library-badge"><?php echo $badge_text; ?></span></div>
			</div>
		<?php } ?>
			<div class="previewer">
				<a class="preview-template" href="<?php echo $prev_url; ?>" target="_blank">
					<img src="<?php echo $settings['preview_image']['url']; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>">
				</a>				
			</div>
			<div class="modal-btns">
				<a href="<?php echo $prev_url; ?>" data-modal="#modal-<?php echo $this->get_id(); ?>" class="modal__trigger">
					<span class="preview-text"><?php echo $settings['preview_text']; ?></span>
				</a>
				<?php if ( $prod_on ) {
					$this->product_link_render(); 
				} else { ?>
					<a href="<?php echo $buy_url; ?>"><span class="buy-text"><?php echo $settings['buy_text']; ?></span></a>
				<?php } ?>
			</div>
		</div>
		
		<div id="modal-<?php echo $this->get_id(); ?>" class="modal modal--align-top modal__bg template-library" role="dialog" aria-hidden="true">
			<?php if ( $topbar_on ) { ?>
			<div class="top-panel">
				<div class="left-pane">
					<h3 class="template-name"><?php echo $temp_name; ?></h3>
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
					<div class="modal-btns">
						<?php if ( $prod_on ) {
							$this->product_link_render(); 
						} else { ?>
							<a href="<?php echo $buy_url; ?>"><span class="button-primary buy-text"><?php echo $settings['buy_text']; ?></span></a>
						<?php } ?>
					</div>
				</div>				
			</div>
			<?php } ?>
		</div>
		
	<?php
	}
	
	protected function continue_shopping_render() {
		$settings 		= $this->get_settings();		
		$prod_id		= $settings['download'];
		
		echo do_shortcode( '[purchase_link id="' . $prod_id . '" price="' . absint( $settings['display_price'] ) . '" text="' . $settings['buy_text'] . '" class="buy-text" style="text"]' );		
	}
	
	protected function checkout_redirect_render() {
		$settings 		= $this->get_settings();		
		$prod_id		= $settings['download']; 
		?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>checkout?edd_action=add_to_cart&download_id=<?php echo $prod_id; ?>"><span class="buy-text"><?php echo $settings['buy_text']; ?></span></a>
		<?php
	}
	
	protected function product_link_render() {
		$settings 		= $this->get_settings(); 
		$redirect_on 	= $settings['checkout_redirect_on'];
		if ( $redirect_on ) {
			$this->checkout_redirect_render();
		} else {
			$this->continue_shopping_render();
		}
		
	}

	protected function _content_template() {}
	
}