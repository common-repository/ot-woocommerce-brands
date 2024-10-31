<?php

class otwoocommercebrandsadmin {
	
	public function __construct(){
		add_action( 'init', array( $this, 'otwcbr_init' ) );

		add_filter( 'woocommerce_settings_tabs_array', array( $this, 'otwcbr_add_tab_woocommerce' ), 40 );
		add_action( 'woocommerce_settings_tabs_otwoocommercebrands', array( $this, 'otwcbr_print_plugin_options' ) );
		add_action( 'woocommerce_update_options_otwoocommercebrands', array( $this, 'otwcbr_update_options' ) );
	}

	public function otwcbr_init() {
		$this->options = $this->otwcbr_initOptions();
	}

	public function otwcbr_update_options() {
		foreach ( $this->options as $option ) {
			woocommerce_update_options( $option );
		}
	}

	public function otwcbr_add_tab_woocommerce( $tabs ) {
		$tabs['otwoocommercebrands'] = otwcbr_e( 'OT Brands settings' );

		return $tabs;
	}

	protected function otwcbr_initOptions() {
		$options = array(
			'general' => array(
				array( 'title' => otwcbr_e( 'General Options' ),
					   'type'  => 'title',
					   'desc'  => '',
					   'id'    => 'otwoocommercebrands_options' ),
				array(
					'title'    => otwcbr_e( 'Brand name show on' ),
					'id'       => 'otwcbr_where_show',
					'default'  => '0',
					'type'     => 'radio',
					'desc_tip' => otwcbr_e( 'Please select where you want show Brand name.' ),
					'options'  => array(
						'0' => otwcbr_e( 'Both categories and product detail page' ),
						'1' => otwcbr_e( 'Only categories ' ),
						'2' => otwcbr_e( 'Only product detail' ),
						'3' => otwcbr_e( 'Dont show in both categories and product detail page' )
					),
				),
				array(
					'title'    => otwcbr_e( 'Brand title' ),
					'id'       => 'otwcbr_brand_title',
					'default'  => 'Brand:',
					'type'     => 'text',
					'desc_tip' => otwcbr_e( 'Leave empty if you dont want to show brand title before brand name(s)' ),
					'options'  => array(
						'0' => otwcbr_e( 'Show as brand(s) title' ),
						'1' => otwcbr_e( 'Show as brand(s) image' )
					),
				),
				
				array(
					'title'    => otwcbr_e( 'Brand display type on product detail page' ),
					'id'       => 'otwcbr_show_image',
					'default'  => '0',
					'type'     => 'radio',
					'desc_tip' => otwcbr_e( 'Please check if you want to see brand image instead of title' ),
					'options'  => array(
						'0' => otwcbr_e( 'Show as brand(s) title' ),
						'1' => otwcbr_e( 'Show as brand(s) image' )
					),
				),
				
				array( 'type' => 'sectionend', 'id' => 'otwoocommercebrands_options' ),

				array( 'title' => otwcbr_e( 'Product Details Page' ),
					   'type'  => 'title',
					   'desc'  => otwcbr_e( 'Predefined brand display positions will work only if your theme does not changed default WooCommerce templates structure or using Omegatheme Wordpress Theme.' ),
					   'id'    => 'otwoocommercebrands_detail_product' ),
				array(
					'title'    => otwcbr_e( 'Brand display position' ),
					'id'       => 'otwcbr_detail_position',
					'default'  => '0',
					'type'     => 'radio',
					'desc_tip' => otwcbr_e( 'Please choose postion where brand show on product details page.' ),
					'options'  => array(
						'0' => otwcbr_e( 'Above short description'),
						'1' => otwcbr_e( 'Below short description' ),
						'2' => otwcbr_e( 'Above Add to cart' ),
						'3' => otwcbr_e( 'Below Add to cart' ),
					),
				),

				array( 'type' => 'sectionend', 'id' => 'otwoocommercebrands_detail_product' ),

				array( 'title' => otwcbr_e( 'Product Category', 'otwoocommercebrands' ),
					   'type'  => 'title',
					   'desc'  => otwcbr_e( 'Predefined brand display positions will work only if your theme does not changed default WooCommerce templates structure or using Omegatheme Wordpress Theme.' ),
					   'id'    => 'otwoocommercebrands_product_category' ),
				array(
					'title'    => otwcbr_e( 'Brand display position on category' ),
					'id'       => 'otwcbr_category_position',
					'default'  => '0',
					'type'     => 'radio',
					'desc_tip' => otwcbr_e( 'Please choose postion where brand show on category products.' ),
					'options'  => array(
						'0' => otwcbr_e( 'Above price' ),
						'1' => otwcbr_e( 'Above title' ),
						'2' => otwcbr_e( 'Below title' ),
						'3' => otwcbr_e( 'Above Add to Cart' ),
						'4' => otwcbr_e( 'Below Add to Cart' )
						
					),
				),
				array( 'type' => 'sectionend', 'id' => 'otwoocommercebrands_product_category' )

			)
		);
		return apply_filters( 'otwoocommercebrands_tab_options', $options );
	}

	public function otwcbr_print_plugin_options() { ?>
		<?php foreach ( $this->options as $id => $tab ) : ?>
			<!-- tab #<?php echo $id ?> -->
			<div class="section" id="otwoocommercebrands_<?php echo $id ?>">
				<?php woocommerce_admin_fields( $this->options[$id] ) ?>
			</div>
		<?php endforeach ?>
	<?php }
}