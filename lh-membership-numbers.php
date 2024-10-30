<?php
/**
 * Plugin Name: LH Membership Numbers
 * Plugin URI: https://lhero.org/portfolio/lh-membership-numbers/
 * Description: Plugin to allow users to login by a prefixed user ID
 * Version: 1.05
 * Text Domain: lh_membership_numbers
 * Author: Peter Shaw
 * Author URI: https://shawfactor.com
*/
/*
*  LH Membership Numbers class
*
*  @description:
*/


if (!class_exists('LH_membership_numbers_plugin')) {

class LH_membership_numbers_plugin {

var $filename;
var $options;
var $opt_name = 'lh_membership_numbers-options';
var $namespace = 'lh_membership_numbers';
var $membership_prefix = 'lh_membership_numbers-membership_prefix';


private static $instance;

private function get_membership_number_prefix($userid = false){

$prefix = $this->options[ $this->membership_prefix ];
    
return apply_filters( 'lh_membership_numbers__number_prefix', $prefix, $userid );
    
    
}

public function email_or_number_login_authenticate( $user, $username, $password ) {

$length = strlen($this->options[ $this->membership_prefix ]);

if ( is_a( $user, 'WP_User' ) ){

return $user;

} elseif (isset($username) && !empty($username) && isset($password) && !empty($password)){




if ( is_email( $username ) ) {

$retrieved = get_user_by( 'email', $username );


} elseif (((substr($username,0,$length)) == $this->options[ $this->membership_prefix ]) or ((substr($username,0,$length)) == strtolower($this->options[ $this->membership_prefix ]))){
    
$username = str_replace( '&', '&amp;', stripslashes( $username ) );

$retrieved = get_user_by( 'id', substr($username, $length) );

}

if ( isset( $retrieved, $retrieved->user_login, $retrieved->user_status ) && 0 == (int) $retrieved->user_status ){
$username = $retrieved->user_login;
}

return wp_authenticate_username_password( null, $username, $password );

}

return $user;


}


public function plugin_menu() {
add_options_page('LH Membership Number Options', 'Membership Numbers', 'manage_options', $this->filename, array($this,"plugin_options"));

}

public function plugin_options() {

if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

  
 // See if the user has posted us some information
    // If they did, the nonce will be set

	if( isset($_POST[ $this->namespace."-backend_nonce" ]) && wp_verify_nonce($_POST[ $this->namespace."-backend_nonce" ], $this->namespace."-backend_nonce" )) {


if ($_POST[ $this->membership_prefix ] and ($_POST[ $this->membership_prefix ] != "")){

$options[ $this->membership_prefix ] = strtoupper(trim(sanitize_text_field($_POST[ $this->membership_prefix ])));

}

if (update_option( $this->opt_name, $options )){

$this->options = get_option($this->opt_name);


?>
<div class="updated"><p><strong><?php _e('Membership Number settings saved', $this->namespace); ?></strong></p></div>
<?php

} 

}


    // settings form

include ('partials/option-settings.php');
    



}

// add a settings link next to deactive / edit
public function add_settings_link( $links, $file ) {

	if( $file == $this->filename ){
		$links[] = '<a href="'. admin_url( 'options-general.php?page=' ).$this->filename.'">Settings</a>';
	}
	return $links;
}

 
public function username_restrictor( $valid, $username ) {


$length = strlen($this->options[ $this->membership_prefix ]);

//echo "foo ".substr($username,$length); exit;

if (((substr($username,0,$length) == $this->options[ $this->membership_prefix ]) or (substr($username,0,$length) == strtolower($this->options[ $this->membership_prefix ]))) and (is_numeric(substr($username,$length))) ){

// the username contains the prefix and a numeric
$valid = false;
}




return $valid;

}

public function wpmu_validate_user_signup( $result ){

$length = strlen($this->options[ $this->membership_prefix ]);

    // there is already an error
    if ( $result['errors']->get_error_message('user_name') ){
        return $result;
} elseif (((substr($result['user_name'],0,$length) == $this->options[ $this->membership_prefix ]) or (substr($result['user_name'],0,$length) == strtolower($this->options[ $this->membership_prefix ]))) and (is_numeric(substr($result['user_name'],$length))) ){
        $result['errors']->add('user_name',  __( 'That username is not allowed.' ) );

}

    return $result;
}

function display_number_shortcode_output($atts) {
    
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'member_id' => get_current_user_id()
    ), $atts ) );
    
    
     if ((is_int($member_id) || ctype_digit($member_id)) && (int)$member_id > 0 ) {
         
         
         return $this->get_membership_number_prefix($member_id).$member_id;
         
     } else {
         
         
         return 'Membership number not available';
         
     }
    
    
}


public function register_shortcodes(){

add_shortcode('lh_membership_numbers_display_number', array($this,'display_number_shortcode_output'));

}

public function add_membership_number_column($columns) {

$columns =  $columns + array('lh_membership_number' => 'Membership Number');

return $columns;

}


public function show_membership_number_column_content($value, $column_name, $user_id) {

	if ( 'lh_membership_number' == $column_name ){
		return $this->get_membership_number_prefix($user_id).$user_id;
	} else {
    return $value;
	}
}
  


  /**
     * Gets an instance of our plugin.
     *
     * using the singleton pattern
     */
    public static function get_instance(){
        if (null === self::$instance) {
            self::$instance = new self();
        }
 
        return self::$instance;
    }

public function __construct() {

$this->filename = plugin_basename( __FILE__ );
$this->options = get_option($this->opt_name);

add_action('admin_menu', array($this,"plugin_menu"));
add_filter('plugin_action_links', array($this,"add_settings_link"), 10, 2);
add_filter( 'validate_username', array( $this, 'username_restrictor' ), 10, 2 );
add_filter( 'wpmu_validate_user_signup', array( $this, 'wpmu_validate_user_signup' ));




//The filters allow login with email or prefixed user ID
//remove_filter( 'authenticate', array($this,"email_or_number_login_authenticate"), 20, 3 );
add_filter( 'authenticate', array($this,"email_or_number_login_authenticate"), 90, 3 );

//Register a shortcode for displaying the membership number
add_action( 'init', array($this,"register_shortcodes"));


add_filter('manage_users_columns', array($this,"add_membership_number_column"));
add_action('manage_users_custom_column',  array($this,"show_membership_number_column_content"), 10, 3);

}


}

$lh_membership_numbers_instance = LH_membership_numbers_plugin::get_instance();

}


?>