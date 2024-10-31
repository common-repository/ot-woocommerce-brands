<?php

class otwoocommercebrands_widget extends WP_Widget {
	
	public $options;

	public $widget_fields = array(
        'title'              => '',
        'show_title'         => '0',
        'show_count'         => '0',
        'show_image'         => '1',
        'show_description'   => '1',
        'hide_empty'         => '0',
        'widgetclass'        => '',
    );

    public function __construct() {
        parent::__construct(
            'otwcbr_widget', // Base ID
            'OT WooCommerce Brands list', // Name
            array(
                'classname'   => 'otwcbr-widget',
                'description' => otwcbr_e('Display a list of your brands on your site.')
            )
        );
        add_action( 'wp_enqueue_scripts', array($this,'otwcbr_widget_style_scripts') );
    }

    function otwcbr_widget_style_scripts() {
        wp_enqueue_style( 'otwcbr_widget_css', OTWCBR_PLUGIN_URL.'/css/otwoocommercebrands.css');
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        foreach ($this->widget_fields as $field => $value) {
            $instance[$field] = strip_tags(stripslashes($new_instance[$field]));
        }
        return $instance;
    }

    function form($instance) {
        global $wp_version;
        foreach ($this->widget_fields as $field => $value) {
            if (array_key_exists($field, $this->widget_fields)) {
                ${$field} = !isset($instance[$field]) ? $value : esc_attr($instance[$field]);
            }
        } ?>

        <div id="<?php echo $this->id; ?>">
            <?php include(dirname(__FILE__) . '/otwoocommercebrands-options.php'); ?>
        </div>

        <?php
    }

    function widget($args, $instance) {
        extract($args);
        foreach ($this->widget_fields as $variable => $value) {
            ${$variable} = !isset($instance[$variable]) ? $this->widget_fields[$variable] : esc_attr($instance[$variable]);
        }

        $brands_list = get_terms( 'otproduct_brand', array(
			'orderby'    => 'name',
			'order'             => 'ASC',
			'hide_empty'	=> $hide_empty
		));
        ?>

        <div class="widget otwcbr-widget <?php echo $widgetclass ?>">
            <?php if($title) echo '<h2 class="otwcbr-widget-title widget-title">'.$title.'</h2>'; ?>
            <?php
            if ( !empty( $brands_list ) && !is_wp_error( $brands_list ) ){
				foreach ( $brands_list as $brand_item ) { 
					echo '<div class="brand-item">';	
					
					if($show_title == 1) {
						if($show_count == 1) {
							echo '<div class="brand-title"><a href="'.get_term_link( $brand_item->slug, 'otproduct_brand' ).'">'.$brand_item->name.'</a> <span class="count">('.$brand_item->count.')</span></div>';
						} else {
							echo '<div class="brand-title"><a href="'.get_term_link( $brand_item->slug, 'otproduct_brand' ).'">'.$brand_item->name.'</a></div>';
						}
					}
					if($show_image == 1) { ?>
						<a href="<?php echo get_term_link( $brand_item->slug, 'otproduct_brand' ) ?>"><img src="<?php echo otwcbr_get_brand_image($brand_item->term_id); ?>"></a>
					<?php }
					if($show_description == 1) {
						echo '<p>'.$brand_item->description.'</p>';
					}
					echo '</div>';
				}
			} 
            ?>
        </div>
        

        <?php 
    }
}