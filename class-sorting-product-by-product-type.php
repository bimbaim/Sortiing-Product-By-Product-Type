<?php
/**
 * Created by: Muhammad Ibrahim
 *  Date : March, 8 2022
 *  Email : baim@whello.id
 */


/**
 * WHSortingByProductType
 *
 * @package MY PACKAGE
 * @since 1.0.0
 */

if( ! class_exists( 'WHSortingByProductType' ) ) :

	/**
	 * WHSortingByProductType
	 * When create new product will be add classification product type
   *  4 => external
	 *  3 => grouped
	 *  2 => variable
	 *  1 => simple
	 *
	 * @since 1.0.0
	 */
	class WHSortingByProductType{

		/**
		 * Instance
		 *
		 * @access private
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 1.0.0
		 * @return object initialized object of class.
		 */
		public static function instance(){
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self;
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
      
			add_action( 'save_post_product', array($this, 'wh_create_or_update_product'), 10, 3);
			add_filter( 'woocommerce_get_catalog_ordering_args', array($this,  'wh_add_producttype_sorting' ) );
			add_filter( 'woocommerce_catalog_orderby', array($this,  'wh_producttype_sorting_orderby' ) );
			add_filter( 'woocommerce_default_catalog_orderby_options', array($this,  'wh_producttype_sorting_orderby' ) );

		}
		
    
    /**
		 * Create or Update Meta Key after Add or Update Product
		 *
		 * @since 1.0.0
		 */
		public function wh_create_or_update_product($post_id, $post, $update){
			if ($post->post_status != 'publish' || $post->post_type != 'product') {
				return;
			}
			
			$product = wc_get_product( $post_id );
      
			if($product->get_type() == 'external'){
					update_post_meta($product->get_id(),  'producttype', 4);
				}
			if($product->get_type() == 'grouped'){
					update_post_meta($product->get_id(),  'producttype', 3);
				}
			if($product->get_type() == 'variable'){
					update_post_meta($product->get_id(),  'producttype', 2);
				}
			if($product->get_type() == 'simple'){
					update_post_meta($product->get_id(),  'producttype', 1);
				}


			// Make something with $product
			// You can also check $update
		}
		

		/**
		 * Adds the ability to sort products in the shop based on the Product Type
		 *
		 * @param array $args the sorting args
		 * @return array updated args
		 */
		public function wh_add_producttype_sorting( $args ) {

			$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

			if ( 'producttype' == $orderby_value ) {
				$args['orderby'] = 'meta_value';
				$args['order'] = 'desc'; // lists SKUs alphabetically 0-9, a-z; change to desc for reverse alphabetical
				$args['meta_key'] = 'producttype';
			}

			return $args;
		}		
		
		
		/**
		 * Add the option to the orderby dropdown.
		 *
		 * @param array $sortby the sortby options
		 * @return array updated sortby
		 */
		public function wh_producttype_sorting_orderby( $sortby ) {

			// Change text above as desired; this shows in the sorting dropdown
			$sortby['producttype'] = __( 'Sort By Product Type', 'woocommerce' );

			return $sortby;
		}		
		
		

	}

	/**
	 * Kicking this off by calling 'instance()' method
	 */
	WHSortingByProductType::instance();
	// OR
	// $my_class_name = MY_CLASS_NAME::instance();

endif;	
