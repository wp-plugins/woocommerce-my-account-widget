<?php
/*
Plugin Name: WooCommerce My Account Widget
Plugin URI: http://wordpress.org/extend/plugins/woocommerce-my-account-widget/
Description: WooCommerce My Account Widget shows order & account data.
Author: Bart Pluijms
Author URI: http://www.geev.nl/
Version: 0.1
*/

class WooCommerceMyAccountWidget extends WP_Widget
{

function WooCommerceMyAccountWidget()
{
	$widget_ops = array('classname' => 'WooCommerceMyAccountWidget', 'description' => __( 'WooCommerce My Account Widget shows order & account data', 'woocommerce-myaccount-widget' ) );
    $this->WP_Widget('WooCommerceMyAccountWidget', __( 'WooCommerce My Account Widget', 'woocommerce-myaccount-widget' ), $widget_ops);
}
function form($instance)
{
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	$show_cartlink = isset( $instance['show_cartlink'] ) ? (bool) $instance['show_cartlink'] : false;
	$show_items = isset( $instance['show_items'] ) ? (bool) $instance['show_items'] : false;
	$show_upload = isset( $instance['show_upload'] ) ? (bool) $instance['show_upload'] : false;
	$show_unpaid = isset( $instance['show_unpaid'] ) ? (bool) $instance['show_unpaid'] : false;
	$show_pending = isset( $instance['show_pending'] ) ? (bool) $instance['show_pending'] : false;
?>
	<p><label for="<?php echo $this->get_field_id('logged_out_title'); ?>"><?php _e('Logged out title:', 'woocommerce-myaccount-widget') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('logged_out_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('logged_out_title') ); ?>" value="<?php if (isset ( $instance['logged_out_title'])) echo esc_attr( $instance['logged_out_title'] ); else echo __('Customer Login', 'woocommerce-myaccount-widget'); ?>" /></p>

	<p><label for="<?php echo $this->get_field_id('logged_in_title'); ?>"><?php _e('Logged in title:', 'woocommerce-myaccount-widget') ?></label>
		<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('logged_in_title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('logged_in_title') ); ?>" value="<?php if (isset ( $instance['logged_in_title'])) echo esc_attr( $instance['logged_in_title'] ); else echo __('Welcome %s', 'woocommerce-myaccount-widget'); ?>" /></p>

   	<p> <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_cartlink') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_cartlink') ); ?>"<?php checked( $show_cartlink ); ?> />
		<label for="<?php echo $this->get_field_id('show_cartlink'); ?>"><?php _e( 'Show link to shopping cart', 'woocommerce-myaccount-widget' ); ?></label><br />
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_items') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_items') ); ?>"<?php checked( $show_items ); ?> />
		<label for="<?php echo $this->get_field_id('show_items'); ?>"><?php _e( 'Show number of items in cart', 'woocommerce-myaccount-widget' ); ?></label><br />
		
		
		<?php if (function_exists('woocommerce_umf_admin_menu')) { ?>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_upload') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_upload') ); ?>"<?php checked( $show_upload ); ?> />
		<label for="<?php echo $this->get_field_id('show_upload'); ?>"><?php _e( 'Show number of uploads left', 'woocommerce-myaccount-widget' ); ?></label><br />
		<?php }?>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_unpaid') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_unpaid') ); ?>"<?php checked( $show_unpaid ); ?> />
		<label for="<?php echo $this->get_field_id('show_unpaid'); ?>"><?php _e( 'Show number of unpaid orders', 'woocommerce-myaccount-widget' ); ?></label><br/>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_pending') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_pending') ); ?>"<?php checked( $show_pending ); ?> />
		<label for="<?php echo $this->get_field_id('show_pending'); ?>"><?php _e( 'Show number of uncompleted orders', 'woocommerce-myaccount-widget' ); ?></label>
	</p>
<?php
}
 
function update($new_instance, $old_instance)
{
    $instance = $old_instance;
	$instance['logged_out_title'] = strip_tags(stripslashes($new_instance['logged_out_title']));
	$instance['logged_in_title'] = strip_tags(stripslashes($new_instance['logged_in_title']));
	$instance['show_cartlink'] = !empty($new_instance['show_cartlink']) ? 1 : 0;
	$instance['show_items'] = !empty($new_instance['show_items']) ? 1 : 0;
	$instance['show_upload'] = !empty($new_instance['show_upload']) ? 1 : 0;
	$instance['show_unpaid'] = !empty($new_instance['show_unpaid']) ? 1 : 0;
	$instance['show_pending'] = !empty($new_instance['show_pending']) ? 1 : 0;
    return $instance;
}
function widget($args, $instance)
{	


	extract($args, EXTR_SKIP);
	global $woocommerce;
	
	$logged_out_title = (!empty($instance['logged_out_title'])) ? $instance['logged_out_title'] : __('Customer Login', 'woocommerce-myaccount-widget');
	$logged_in_title = (!empty($instance['logged_in_title'])) ? $instance['logged_in_title'] : __('Welcome %s', 'woocommerce-myaccount-widget');
	
	echo $before_widget;
    
	//check if user is logged in 
	if ( is_user_logged_in() ) { 
		$c = (isset($instance['show_cartlink']) && $instance['show_cartlink']) ? '1' : '0';
		$it = (isset($instance['show_items']) && $instance['show_items']) ? '1' : '0';
		$u = (isset($instance['show_upload']) && $instance['show_upload']) ? '1' : '0';
		$up = (isset($instance['show_unpaid']) && $instance['show_unpaid']) ? '1' : '0';
		$p = (isset($instance['show_pending']) && $instance['show_pending']) ? '1' : '0';
	
		$user = get_user_by('id', get_current_user_id());
		echo '<div class=login>';
		if ( $logged_in_title ) echo $before_title . sprintf( $logged_in_title, ucwords($user->first_name) ) . $after_title;
		
		if($c) {echo '<p class=clearfix><a class="woo-ma-button cart-link" href="'.$woocommerce->cart->get_cart_url() .'" title="'. __('View your shopping cart','woocommerce-myaccount-widget').'">'.__('View your shopping cart','woocommerce-myaccount-widget').'</a></p>';}
		
		$notcompleted=0;
		$uploadfile=0;
		$notpaid=0;
		$customer_id = get_current_user_id();
		$args = array(
			'numberposts'     => -1,
			'meta_key'        => '_customer_user',
			'meta_value'	  => $customer_id,
			'post_type'       => 'shop_order',
			'post_status'     => 'publish'
		);
		$customer_orders = get_posts($args);
		if ($customer_orders) {
			foreach ($customer_orders as $customer_order) :
				$woocommerce1=0;
				$order = new WC_Order();
				$order->populate( $customer_order );
				$status = get_term_by('slug', $order->status, 'shop_order_status');
				if($status->name!='completed' && $status->name!='cancelled'){ $notcompleted++; }
				
				
			/* upload files */
		if (function_exists('woocommerce_umf_admin_menu')) {
			if(get_max_upload_count($order) >0 ) {
				$j=1;
				foreach ( $order->get_items() as $order_item ) {
					$max_upload_count=get_max_upload_count($order,$order_item['product_id']);
					$i=1;
					$upload_count=0;
					while ($i <= $max_upload_count) {
						if(get_post_meta( $order->id, '_woo_umf_uploaded_file_name_' . $j, true )!="") {$upload_count++;}
						$i++;
						$j++;
					}
					/* toon aantal nog aan te leveren bestanden */
					$upload_count=$max_upload_count-$upload_count;
					$uploadfile+=$upload_count;
				}
			}
		}
		if (in_array($order->status, array('on-hold','pending', 'failed'))) { $notpaid++;}
		endforeach;
		}
		echo '<ul class=clearfix>';
			if($it) {echo '<li class="woo-ma-link item"><a class="cart-contents-new" href="'.$woocommerce->cart->get_cart_url().'" title="'. __('View your shopping cart', 'woocommerce-myaccount-widget').'">'.sprintf(_n('<span>%d</span> product in your shopping cart', '<span>%d</span> products in your shoppingcart', $woocommerce->cart->cart_contents_count, 'woocommerce-myaccount-widget'), $woocommerce->cart->cart_contents_count).'</a></li>';}
			if($u && function_exists('woocommerce_umf_admin_menu')) {  echo '<li class="woo-ma-link upload"><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Upload files', 'woocommerce-myaccount-widget').'">'.sprintf(_n('<span>%d</span> file to upload', '<span>%d</span> files to upload', $uploadfile, 'woocommerce-myaccount-widget'), $uploadfile).'</a></li>'; }
			if($up) {echo '<li class="woo-ma-link paid"><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Pay orders', 'woocommerce-myaccount-widget').'">'.sprintf(_n('<span>%d</span> payment required', '<span>%d</span> payments required', $notpaid, 'woocommerce-myaccount-widget'), $notpaid).'</a></li>';}
			if($p) { echo '<li class="woo-ma-link pending"><a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('View uncompleted orders', 'woocommerce-myaccount-widget').'">'.sprintf(_n('<span>%d</span> order pending', '<span>%d</span> orders pending', $notcompleted, 'woocommerce-myaccount-widget'), $notcompleted).'</a></li>';}
		echo '</ul>';
		echo '<p><a class="woo-ma-button myaccount-link" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('My Account','woocommerce-myaccount-widget').'">'.__('My Account','woocommerce-myaccount-widget').'</a>';
	}
	else {
		echo '<div class=logout>';
		// user is not logged in
		if ( $logged_out_title ) echo $before_title . $logged_out_title . $after_title;
		
		// login form
		$args = array(
			'echo' => true,
			'redirect' => site_url( $_SERVER['REQUEST_URI'] ), 
			'form_id' => 'loginform',
			'label_username' => __( 'Username' ),
			'label_password' => __( 'Password' ),
			'label_remember' => __( 'Remember Me' ),
			'label_log_in' => __( 'Log In' ),
			'id_username' => 'user_login',
			'id_password' => 'user_pass',
			'id_remember' => 'rememberme',
			'id_submit' => 'wp-submit',
			'remember' => false,
			'value_username' => NULL,
			'value_remember' => false ); 
		wp_login_form( $args );
		echo '<a href="'. wp_lostpassword_url().'">'. __('Lost password?', 'woocommerce-myaccount-widget').'</a>';
		echo '<a class="woo-ma-button register-link" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Register','woocommerce-myaccount-widget').'">'.__('Register','woocommerce-myaccount-widget').'</a>';
		echo '<p><a class="woo-ma-button cart-link" href="'.$woocommerce->cart->get_cart_url() .'" title="'. __('View your shopping cart','woocommerce-myaccount-widget').'">'.__('View your shopping cart','woocommerce-myaccount-widget').'</a></p>';
	}
	echo '</div>';
    echo $after_widget;
}
 
}
load_plugin_textdomain('woocommerce-myaccount-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
add_action( 'widgets_init', create_function('', 'return register_widget("WooCommerceMyAccountWidget");') );?>