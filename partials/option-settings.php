<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
<form name="lh_membership_numbers-backend_form" method="post" action="">
<?php wp_nonce_field( $this->namespace."-backend_nonce", $this->namespace."-backend_nonce", false ); ?>
<table class="form-table">
<tr valign="top">
<th scope="row"><label for="<?php echo $this->membership_prefix; ?>"><?php _e("Membership Number Prefix", $this->namespace ); ?></label></th>
<td>
<input type="text" name="<?php echo $this->membership_prefix; ?>" id="<?php echo $this->membership_prefix; ?>" value="<?php echo $this->options[ $this->membership_prefix ]; ?>" size="10" />
</td>
</tr>
</table>
<?php submit_button( __( 'Save Changes', $this->namespace ) ); ?>
</form>