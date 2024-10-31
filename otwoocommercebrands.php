<?php
/*
Plugin Name: WooCommerce Brands
Plugin URI: https://www.omegatheme.com/
Description:  Add Brands taxonomy for products from WooCommerce plugin.
Author: Omegatheme
Version: 1.1.1
Company: XIPAT Flexible Solutions 
Author URI: http://www.omegatheme.com
*/

define('OTWCBR_PLUGIN_NAME', 'WooCommerce Brands');
define('OTWCBR_PLUGIN_VERSION', '1.1.1');
define('OTWCBR_PLUGIN_URL',plugins_url(basename(plugin_dir_path(__FILE__ )), basename(__FILE__)));

function otwcbr_e($text, $params=null) {
    if (!is_array($params)) {
        $params = func_get_args();
        $params = array_slice($params, 1);
    }
    return vsprintf(__($text, 'otwoocommercebrands'), $params);
}

include "otwoocommercebrands-widget.php";

class OtWoocommerceBrands {
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'otwcbr_plugin_install' ) );
		register_uninstall_hook( __FILE__, array( $this, 'otwcbr_plugin_uninstall' ) );

		add_action( 'init', array( $this, 'otwcbr_register_brand_taxonomy'));
		add_action( 'init', array( $this, 'otwcbr_init_brand_taxonomy_meta'));

		add_action( 'admin_init', array( $this, 'otwcbr_admin_scripts' ) );
		add_action( 'woocommerce_before_single_product', array( $this, 'otwcbr_single_product' ) );
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'otwcbr_categories_product' ) );

		// /*Setting*/
		add_action( 'plugins_loaded', array( $this, 'otwcbr_settings_init' ) );
		add_action( 'widgets_init', array( $this, 'otwoocommercebrands_register_widgets' ) );

		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'otwcbr_plugin_action_links' ) );
		add_action('otproduct_brand_add_form_fields',array( $this, 'otwcbr_addcategoryimage' ),10,2);
		add_action('otproduct_brand_edit_form_fields',array( $this, 'otwcbr_editcategoryimage' ));
		add_action('edit_term',array( $this, 'otwcbr_categoryimagesave' ));
		add_action('create_term',array( $this, 'otwcbr_categoryimagesave' ));
	}

	/*-------------------------------- Activation --------------------------------*/
	function otwcbr_plugin_install() {
		global $wp_version;
		If ( version_compare( $wp_version, "4.0", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 4.0 or higher." );
		}
		/**
		 * Check if WooCommerce is active
		 **/
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
		    deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin required WooCommerce plugin installed and activated. Please <a href='http://www.woothemes.com/woocommerce/' target='_blank'>download and install WooCommerce plugin</a>." );
		}
	}

	function otwcbr_plugin_uninstall($taxonomy) {
		unregister_taxonomy($taxonomy);
	}

	function otwcbr_admin_scripts() {
		wp_enqueue_script( 'otwcbr-script-admin', OTWCBR_PLUGIN_URL . '/js/otwoocommercebrands-admin.js', array(), false, true );
		wp_enqueue_media();
	}

	function otwcbr_settings_init() {
		require_once( 'otwoocommercebrands-admin.php' );
		$init = new otwoocommercebrandsadmin();
	}

	function otwcbr_plugin_action_links( $links ) {
		$action_links = array(
			'settings'	=>	'<a href="admin.php?page=wc-settings&tab=otwoocommercebrands" title="' . __( 'Settings', 'otwoocommercebrands' ) . '">' . __( 'Settings', 'otwoocommercebrands' ) . '</a>',
		);

		return array_merge( $action_links, $links );
	}

	/*-------------------------------- Taxonomy --------------------------------*/

	function otwcbr_register_brand_taxonomy() {
		$labels = array(
			'name' => _x( 'Brands', 'taxonomy general name' ),
			'singular_name' => _x( 'Brand', 'taxonomy singular name' ),
			'search_items' =>  otwcbr_e( 'Search Brands' ),
			'all_items' => otwcbr_e( 'All Brands' ),
			'parent_item' => otwcbr_e( 'Parent Brand' ),
			'parent_item_colon' => otwcbr_e( 'Parent Brands:' ),
			'edit_item' => otwcbr_e( 'Edit Brands' ),
			'update_item' => otwcbr_e( 'Update Brands' ),
			'add_new_item' => otwcbr_e( 'Add New Brand' ),
			'new_item_name' => otwcbr_e( 'New Brand Name' ),
			'menu_name' => otwcbr_e( 'Brands' ),
		);    

	    register_taxonomy("otproduct_brand",
			array("product"),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'product-brand', 'with_front' => true ),
				'show_admin_column' => true
			)
		);
	}
	function otwcbr_init_brand_taxonomy_meta() {
		$prefix = 'otwcbr_';

		$config = array(
			'id' => 'otwcbr_box',          // meta box id, unique per meta box
			'title' => 'Brands settings',          // meta box title
			'pages' => array('otproduct_brand'),        // taxonomy name, accept categories, post_tag and custom taxonomies
			'context' => 'normal',            // where the meta box appear: normal (default), advanced, side; optional
			'fields' => array(),            // list of meta fields (can be added by field arrays)
			'local_images' => false,          // Use local or hosted images (meta box images for add/remove)
			'use_with_theme' => false          //change path if used with theme set to true, false for a plugin or anything else for a custom path
		);
	}

	/*-------------------------------- Taxonomy Image--------------------------------*/

	function otwcbr_addcategoryimage($taxonomy){ ?>
	    <div class="form-field">
			<label for="otwcbr-image"><?php echo otwcbr_e('Image') ?></label>
			<input type="text" name="otwcbr-image" id="otwcbr-image" value="<?php if(isset($arrTestiData->client_avtar)) {  echo stripslashes($arrTestiData->client_avtar);}  ?>">
	        <input id="otwcbr-image-button" type="button" value="<?php echo otwcbr_e('Upload Image') ?>" />
			<p class="description"><?php echo otwcbr_e('Click on the text box to add taxonomy/category image') ?>.</p>
		</div>
		
	<?php }

	function otwcbr_editcategoryimage($taxonomy){ ?>
		<tr class="form-field">
			<th scope="row" valign="top"><label for="otwcbr-image"><?php echo otwcbr_e('Image') ?></label></th>
			<td>
			<?php 
			if(get_option('_taxonomy_image'.$taxonomy->term_id) != ''){ ?>
				<img src="<?php echo get_option('_taxonomy_image'.$taxonomy->term_id); ?>" width="100"  height="100"/>
			<?php	
			}
			?><br />
			<input type="text" name="otwcbr-image" id="otwcbr-image" value="<?php echo get_option('_taxonomy_image'.$taxonomy->term_id); ?>" />
			<input id="otwcbr-image-button" type="button" value="<?php echo otwcbr_e('Upload Image') ?>" />
			<p class="description"><?php echo otwcbr_e('Click on the text box to add taxonomy/category image') ?>.</p>
			</td>
		</tr>
	<?php }

	function otwcbr_categoryimagesave($term_id){
	    if(isset($_POST['otwcbr-image'])){
	        if(isset($_POST['otwcbr-image']))
	            update_option('_taxonomy_image'.$term_id,$_POST['otwcbr-image'] );
	    }
	}

	/*-------------------------------- Register Widget --------------------------------*/

	function otwoocommercebrands_register_widgets() {
		register_widget( 'otwoocommercebrands_widget' );
	}

	/*-------------------------------- Show brand --------------------------------*/

	function otwcbr_single_product( $post_ID ) {

		global $post;
		global $wp_query;

		$product_id = $post->ID;
		
		@$where_show = get_option( 'otwcbr_where_show' );
		@$ob_show_image = get_option( 'otwcbr_show_image' );

		if(isset($_GET['otwcbr_show_image'])) {
			$ob_show_image = intval($_GET['otwcbr_show_image']);
		}

		@$ob_brand_title = get_option( 'otwcbr_brand_title' );

		if ( $where_show == 1 || $where_show == 3 ) {
			return;
		}
		if ( is_admin() || ! $wp_query->post->ID ) {
			return;
		}

		$brands_list =  wp_get_object_terms($product_id, 'otproduct_brand');
		
		$brands_list_output = '';
		$brand_image_output = '';
		$brands_list_comma = ', ';
		$i = 0;
		
		foreach ( $brands_list as $brand ) {

				$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'otproduct_brand' ).'">'.$brand->name.'</a>';

				if($i < count($brands_list) - 1) {
					$brands_list_output .= $brands_list_comma;
				}
				
				$i++;
			
		}

		if(count($brands_list) > 0) {
			if($ob_show_image == 0) {
				if($ob_brand_title <> '') {
					$show = '<div class="brands"><span class="bold">'.$ob_brand_title.'</span> '.$brands_list_output.'</div>';
				}
				else {
					$show = '<div class="brands">'.$brands_list_output.'</div>';
				}
			} else {
				$brand_image = otwcbr_get_brand_image($brand->term_id);
				if($ob_brand_title <> '') {
					$show = '<div class="brands"><span class="bold">'.$ob_brand_title.'</span><a href="'.get_term_link( $brand->slug, 'otproduct_brand' ).'"><img class="brand-image" src="'.$brand_image.'"></a></div>';
				}
				else {
					$show = '<div class="brands"><a href="'.get_term_link( $brand->slug, 'otproduct_brand' ).'"><img class="brand-image" src="'.$brand_image.'"></a></div>';
				}
			}
			
			
			
			@$brand_position = get_option( 'otwcbr_detail_position', 0 );

			switch ( $brand_position ) {
				case 1:
					echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertAfter('.single-product .woocommerce-product-details__short-description');
							jQuery('" . $show . "').insertAfter('.single-product .product-short-description');
						});
					</script>
					";
					break;
				case 2:
					echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertBefore('.single-product div.product form.cart');
							jQuery('" . $show . "').insertBefore('.single-product .addtocart-area');
						});
					</script>
					";
					break;
				case 3:
					echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertAfter('.single-product div.product form.cart');
							jQuery('" . $show . "').insertAfter('.single-product .addtocart-area');
						});
					</script>
					";
					break;
				default:
					echo "<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertBefore('.single-product .woocommerce-product-details__short-description');
							jQuery('" . $show . "').insertBefore('.single-product .product-short-description');
						});
					</script>
					";

			}
		}
	}

	function otwcbr_categories_product() {
		global $post;

		@$where_show = get_option( 'otwcbr_where_show' );

		if ( $where_show == 2 || $where_show == 3 ) {
			return;
		}
		if ( is_admin() || ! $post->ID ) {
			return;
		}

		$product_id = $post->ID;
		
		$brands_list =  wp_get_object_terms($product_id, 'otproduct_brand');
		
		@$ob_brand_title = get_option( 'otwcbr_brand_title' );

		$brands_list_output = '';
		$brands_list_comma = ', ';
		$i = 0;
		
		foreach ( $brands_list as $brand ) {

			$brands_list_output .= '<a href="'.get_term_link( $brand->slug, 'otproduct_brand' ).'">'.$brand->name.'</a>';

			if($i < count($brands_list) - 1) {
				$brands_list_output .= $brands_list_comma;
			}
			
			$i++;
		}

		if(count($brands_list) > 0) {

			if($ob_brand_title <> '') {
				$show = '<div class="brands"><span class="bold">'.$ob_brand_title.'</span> '.$brands_list_output.'</div>';
			}
			else {
				$show = '<div class="brands">'.$brands_list_output.'</div>';
			}

			@$brand_position = get_option( 'otwcbr_category_position', 0 );

			switch ( $brand_position ) {
				case 1:
					echo "
						<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" . $show . "').insertBefore('.tax-product_cat li.post-" . $post->ID . " h2');
								jQuery('" . $show . "').insertBefore('.tax-product_cat .spacer .product-name');
							});
						</script>
						";
					break;
				case 2:
					echo "
					<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertAfter('.tax-product_cat li.post-" . $post->ID . " h2');
							jQuery('" . $show . "').insertAfter('.tax-product_cat .spacer .product-name');
						});
					</script>
					";
					break;
				case 3:
					echo "
					<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertBefore('.tax-product_cat li.post-" . $post->ID . " a.add_to_cart_button');
							jQuery('" . $show . "').insertBefore('.tax-product_cat .spacer .addtocart-area');
						});
					</script>
					";
					break;
				case 4:
					echo "
						<script type='text/javascript'>
							jQuery(document).ready(function(){
								jQuery('" . $show . "').insertAfter('.tax-product_cat li.post-" . $post->ID . " a.add_to_cart_button');
								jQuery('" . $show . "').insertAfter('.tax-product_cat .spacer .addtocart-area');
							});
						</script>
						";
					break;
				default :
					echo "
					<script type='text/javascript'>
						jQuery(document).ready(function(){
							jQuery('" . $show . "').insertBefore('.tax-product_cat .spacer .product-price');
							jQuery('" . $show . "').insertBefore('.tax-product_cat li.post-" . $post->ID . " span.price');
						});
					</script>
					";
			}
			
		}
	}
}

$otwoocommercebrands = new OtWoocommerceBrands();

function otwcbr_get_brand_image($term_id){
	return get_option('_taxonomy_image'.$term_id);	
}
