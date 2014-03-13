<?php
/*
Plugin Name: WooCommerce My Account Widget
Plugin URI: http://wordpress.org/extend/plugins/woocommerce-my-account-widget/
Description: WooCommerce My Account Widget shows order & account data.
Author: Bart Pluijms
Author URI: http://www.geev.nl/
Version: 0.2.9.2
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
	$show_logout_link = isset( $instance['show_logout_link'] ) ? (bool) $instance['show_logout_link'] : false;
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
		<label for="<?php echo $this->get_field_id('show_pending'); ?>"><?php _e( 'Show number of uncompleted orders', 'woocommerce-myaccount-widget' ); ?></label><br>
		<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('show_logout_link') ); ?>" name="<?php echo esc_attr( $this->get_field_name('show_logout_link') ); ?>"<?php checked( $show_logout_link ); ?> />
		<label for="<?php echo $this->get_field_id('show_logout_link'); ?>"><?php _e( 'Show logout link', 'woocommerce-myaccount-widget' ); ?></label>
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
	$instance['show_logout_link'] = !empty($new_instance['show_logout_link']) ? 1 : 0;
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
		$lo = (isset($instance['show_logout_link']) && $instance['show_logout_link']) ? '1' : '0';
	
	// redirect url after login / logout
	if(is_multisite()) { $woo_ma_home=network_home_url(); } else {$woo_ma_home=home_url();}
	
		$user = get_user_by('id', get_current_user_id());
		echo '<div class=login>';
		if($user->first_name!="") { $uname=$user->first_name;} else { $uname=$user->display_name; }
		if ( $logged_in_title ) echo $before_title . sprintf( $logged_in_title, ucwords($uname) ) . $after_title;
		
		if($c) {echo '<p><a class="woo-ma-button cart-link woo-ma-cart-link" href="'.$woocommerce->cart->get_cart_url() .'" title="'. __('View your shopping cart','woocommerce-myaccount-widget').'">'.__('View your shopping cart','woocommerce-myaccount-widget').'</a></p>';}
		
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
		echo '<ul class="clearfix woo-ma-list">';
			if($it) {
				echo '<li class="woo-ma-link item">
						<a class="cart-contents-new" href="'.$woocommerce->cart->get_cart_url().'" title="'. __('View your shopping cart', 'woocommerce-myaccount-widget').'">
							<span>'.$woocommerce->cart->cart_contents_count.'</span> '
							._n('product in your shopping cart','products in your shoppingcart', $woocommerce->cart->cart_contents_count, 'woocommerce-myaccount-widget' ).'
						</a>
					</li>';
			} 
			if($u && function_exists('woocommerce_umf_admin_menu')) {  
				echo '<li class="woo-ma-link upload">
						<a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Upload files', 'woocommerce-myaccount-widget').'">
							<span>'.$uploadfile.'</span> '
							._n('file to upload','files to upload', $uploadfile, 'woocommerce-myaccount-widget' ).'
						</a>
					</li>';
			} 
			if($up) {
				echo '<li class="woo-ma-link paid">
						<a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Pay orders', 'woocommerce-myaccount-widget').'">
							<span>'.$notpaid.'</span> '
							._n('payment required','payments required', $notpaid, 'woocommerce-myaccount-widget' ).'
						</a>
					</li>';
			} 
			if($p) {
				echo '<li class="woo-ma-link pending">
						<a href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('View uncompleted orders', 'woocommerce-myaccount-widget').'">
							<span>'.$notcompleted.'</span> '
							._n('order pending','orders pending', $notcompleted, 'woocommerce-myaccount-widget' ).'
						</a>
					</li>';
			} 
		echo '</ul>';
		echo '<p><a class="woo-ma-button woo-ma-myaccount-link myaccount-link" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('My Account','woocommerce-myaccount-widget').'">'.__('My Account','woocommerce-myaccount-widget').'</a></p>';
		if($lo==1) { echo '<p><a class="woo-ma-button woo-ma-logout-link logout-link" href="'.wp_logout_url($woo_ma_home).'" title="'. __('Log out','woocommerce-myaccount-widget').'">'.__('Log out','woocommerce-myaccount-widget').'</a></p>'; }
	}
	else {
		echo '<div class=logout>';
		// user is not logged in
		if ( $logged_out_title ) echo $before_title . $logged_out_title . $after_title;
		if(isset($_GET['login']) && $_GET['login']=='failed') {
			echo '<p class="woo-ma-login-failed woo-ma-error">';
			_e('Login failed, please try again','woocommerce-myaccount-widget');
			echo '</p>';
		}
		// login form
		$args = array(
			'echo' => true,
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
		echo '<a class="woo-ma-link woo-ma-lost-pass" href="'. wp_lostpassword_url().'">'. __('Lost password?', 'woocommerce-myaccount-widget').'</a>';
		
		if(get_option('users_can_register')) {  
			echo ' <a class="woo-ma-button woo-ma-register-link register-link" href="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'" title="'. __('Register','woocommerce-myaccount-widget').'">'.__('Register','woocommerce-myaccount-widget').'</a>';
		}
		
		echo '<p><a class="woo-ma-button woo-ma-cart-link cart-link" href="'.$woocommerce->cart->get_cart_url() .'" title="'. __('View your shopping cart','woocommerce-myaccount-widget').'">'.__('View your shopping cart','woocommerce-myaccount-widget').'</a></p>';
	}
	echo '</div>';
    echo $after_widget;
}

}
load_plugin_textdomain('woocommerce-myaccount-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' ); 
add_action( 'widgets_init', create_function('', 'return register_widget("WooCommerceMyAccountWidget");') );

/**
* Redirect to homepage after failed login 
* Since 0.2.3
*/
add_action('wp_login_failed', 'wma_login_fail'); 
 
function wma_login_fail($username){
    // Get the reffering page, where did the post submission come from?
    $referer = parse_url($_SERVER['HTTP_REFERER']);
	$referer= '//'.$referer['host'].'/'.$referer['path'];
 
    // if there's a valid referrer, and it's not the default log-in screen
    if(!empty($referer) && !strstr($referer,'wp-login') && !strstr($referer,'wp-admin')){
        // let's append some information (login=failed) to the URL for the theme to use
        wp_redirect($referer . '?login=failed'); 
    exit;
    }
}
?>