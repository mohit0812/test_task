<?php

/**
 * Bright WooCommerce Functions
 * Author: WpBranch
 * Since : 1.0
 */

/**
 * Adding WooCommerce 3.0 gallery and Zoom Support
 */

if(!function_exists('bright_woocommerce_theme_setup')){
	function bright_woocommerce_theme_setup() {
		$ep_need_woo_zoom 				= cs_get_option('ep_need_woo_zoom');
		$ep_need_woo_lightbox 			= cs_get_option('ep_need_woo_lightbox');
		$ep_need_woo_lightbox_slider 	= cs_get_option('ep_need_woo_lightbox_slider');

		if( $ep_need_woo_zoom == true ){
			add_theme_support( 'wc-product-gallery-zoom' );
		}
		if( $ep_need_woo_lightbox == true ){
			add_theme_support( 'wc-product-gallery-lightbox' );
		}
		if( $ep_need_woo_lightbox_slider == true ){
			add_theme_support( 'wc-product-gallery-slider' );
		}
	}
}

add_action( 'after_setup_theme', 'bright_woocommerce_theme_setup' );

/**
 * Enqueue WooCommerce scripts and styles.
 */

if(!function_exists('bright_woocommerce_scripts')){
	function bright_woocommerce_scripts() {

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_style( 'bright-woocommerce-main', get_template_directory_uri() . '/css/woocommerce-main' . $suffix . '.css', array(), '1.0' );
	}
}
add_action( 'wp_enqueue_scripts', 'bright_woocommerce_scripts' );



/**
 * Check WooCommerce
 */

if ( ! function_exists( 'bright_is_woocommerce_activated' ) ) {
	function bright_is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}


/**
 * Content area class
 */

add_filter( 'bright_content_area_class', 'bright_wocommerce_contet_area_class' );
if(!function_exists('bright_wocommerce_contet_area_class')){
	function bright_wocommerce_contet_area_class ( $class ) {

		if( is_shop() ){

			$ep_shop_layout = cs_get_option('ep_shop_layout');

			if( $ep_shop_layout == 'right' ){
				$class = 'col-md-9';
			}elseif ( $ep_shop_layout == 'left' ) {
				$class = 'col-md-9 col-md-push-3';
			}elseif( $ep_shop_layout = 'full_width' ){
				$class = 'col-md-12';
			}

		}

		return $class;
	}
}


/**
 * Widget area class
 */

add_filter( 'bright_widget_area_class', 'bright_woocomerce_widget_area_class' );
if(!function_exists('bright_woocomerce_widget_area_class')){
	function bright_woocomerce_widget_area_class ( $class ) {
		
		if( is_shop() ){

			$ep_shop_layout = cs_get_option('ep_shop_layout');

			if( $ep_shop_layout == 'right' ){
				$class = 'col-md-3';
			}elseif ( $ep_shop_layout == 'left' ) {
				$class = 'col-md-3 col-md-pull-9';
			}elseif( $ep_shop_layout = 'full_width' ){
				$class = '';
			}

		}
		
		return $class;

	}
}



/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */

if(!function_exists('bright_woocommerce_widgets_init')){
	function bright_woocommerce_widgets_init() {
		register_sidebar( array(
			'name'          => esc_html__( 'WooCommerce Shop', 'bright' ),
			'id'            => 'woocommerce_shop',
			'description'   => esc_html__( 'Add widgets here. It will be shown to the WooCommerce shop and few other WooCommerce pages.', 'bright' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
		register_sidebar( array(
			'name'          => esc_html__( 'WooCommerce Product', 'bright' ),
			'id'            => 'woocommerce_product',
			'description'   => esc_html__( 'Add widgets here. It will be shown to the WooCommerce product page.', 'bright' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	}
}

add_action( 'widgets_init', 'bright_woocommerce_widgets_init' );



/**
 * Woo Page Title
 */


add_filter( 'bright_theme_page_title', 'bright_woocommerce_page_title' );

if(!function_exists('bright_woocommerce_page_title')){
	function bright_woocommerce_page_title( $title ){
		if( is_shop() || is_singular( 'product' ) ){
			$title = woocommerce_page_title( false );
		}

		return $title;
	}
}


/**
 * Remove default Woo Page Title
 */

add_filter( 'woocommerce_show_page_title' , 'bright_woo_hide_page_title' );

if(!function_exists('bright_woo_hide_page_title')){
	function bright_woo_hide_page_title() {
		return false;
	}
}

/**
 * Shop product columns
 */

add_filter('loop_shop_columns', 'bright_woocommerce_product_loop_columns');

if (!function_exists('bright_woocommerce_product_loop_columns')) {
	function bright_woocommerce_product_loop_columns() {
		$ep_shop_loop_column = cs_get_option('ep_shop_loop_column');
		return $ep_shop_loop_column;
	}
}

/**
 * Adding column number to body class
 */

add_filter('body_class', 'bright_woocommerce_body_class');

if(!function_exists('bright_woocommerce_body_class')){
	function bright_woocommerce_body_class($classes) {
		$ep_shop_loop_column = cs_get_option('ep_shop_loop_column');

	    if ( is_woocommerce()) {
	        $classes[] = 'bright-product-columns-'.$ep_shop_loop_column;
	    }
	    return $classes;
	}
}

/**
 * Shop filter wrapper
 */

add_action( 'woocommerce_before_shop_loop', 'bright_woocommerce_shop_filter_wrapper_start', 10 );
add_action( 'woocommerce_before_shop_loop', 'bright_woocommerce_shop_filter_wrapper_end', 40 );

if(!function_exists('bright_woocommerce_shop_filter_wrapper_start')){
	function bright_woocommerce_shop_filter_wrapper_start(){
		echo '<div class="bright-woocommerce-shop-filter-wrapper clearfix">';
	}
}

if(!function_exists('bright_woocommerce_shop_filter_wrapper_end')){
	function bright_woocommerce_shop_filter_wrapper_end(){
		echo '</div>';
	}
}

/**
 * Shop loop item wrapper
 */

add_action( 'woocommerce_before_shop_loop_item_title', 'bright_woocommerce_shop_loop_wrapper_start', 20 );

if(!function_exists('bright_woocommerce_shop_loop_wrapper_start')){
	function bright_woocommerce_shop_loop_wrapper_start(){
		echo '<div class="bright-product-wrapper-inner">';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'bright_woocommerce_shop_loop_wrapper_end', 20 );

if(!function_exists('bright_woocommerce_shop_loop_wrapper_end')){
	function bright_woocommerce_shop_loop_wrapper_end(){
		echo '</div>';
	}
}

/**
 * Shop loop price and review wrapper
 */

add_action( 'woocommerce_after_shop_loop_item', 'bright_woocommerce_shop_price_wrapper_start', 5 );

if(!function_exists('bright_woocommerce_shop_price_wrapper_start')){
	function bright_woocommerce_shop_price_wrapper_start(){
		echo '<div class="bright-price-rating-wrapper">';
	}
}

add_action( 'woocommerce_after_shop_loop_item', 'bright_woocommerce_shop_price_wrapper_end', 6 );

if(!function_exists('bright_woocommerce_shop_price_wrapper_end')){
	function bright_woocommerce_shop_price_wrapper_end(){
		echo '</div>';
	}
}


/**
 * Move the loop price
 */

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 5 );

/**
 * Move the loop review
 */

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 5 );

/**
 * Move the loop link close
 */

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );


add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_open', 25 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 10 );

/**
 * Related products
 */

add_filter( 'woocommerce_output_related_products_args', 'bright_woocommerce_related_products_args' );

if(!function_exists('bright_woocommerce_related_products_args')){
	function bright_woocommerce_related_products_args( $args ) {
		$args['posts_per_page'] = cs_get_option('ep_related_per_page');
		$args['columns'] = cs_get_option('ep_related_loop_column');

		return $args;
	}
}

/**
 * Single product wrapper
 */

add_action( 'woocommerce_before_single_product_summary', 'bright_woocommerce_single_product_wrapper_start', 5 );

if(!function_exists('bright_woocommerce_single_product_wrapper_start')){
	function bright_woocommerce_single_product_wrapper_start(){
		echo '<div class="bright-single-product-wrapper-inner clearfix">';
	}
}


add_action( 'woocommerce_after_single_product_summary', 'bright_woocommerce_single_product_wrapper_end', 5 );

if(!function_exists('bright_woocommerce_single_product_wrapper_end')){
	function bright_woocommerce_single_product_wrapper_end(){
		echo '</div>';
	}
}


/**
 * Number of products in shop page
 */

add_filter( 'loop_shop_per_page', 'bright_woocommerce_shop_number_of_products', 20 );

if(!function_exists('bright_woocommerce_shop_number_of_products')){
	function bright_woocommerce_shop_number_of_products( $number ){
		return cs_get_option('ep_shop_number_of_products');
	}
}