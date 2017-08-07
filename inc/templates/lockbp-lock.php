<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
get_header();

//Retrieve Settings
$lockbp_settings = get_option( 'lockbp_settings' );
$loginurl = wp_login_url();
$registerurl = wp_registration_url();
?>
<!-- Overlay content -->
<div class="lockbp-content">
  	<p class="lockbp-message">
	  	<!-- echo the message -->
		<?php if( isset( $lockbp_settings['locked_content'] ) && $lockbp_settings['locked_content']['type'] == 'message' ) {?>
			<?php echo $lockbp_settings['locked_content']['content'];?>
		<?php }?>

		<!-- echo the shortcodde -->
		<?php if( isset( $lockbp_settings['locked_content'] ) && $lockbp_settings['locked_content']['type'] == 'shortcode' ) {?>
			<?php $locked_content = $lockbp_settings['locked_content']['content'];?>
			<?php echo do_shortcode( "$locked_content" );?>
		<?php }?>
	</p>

	<div class="lockbp-locked-actions">
		<input type="button" value="<?php _e( 'Login', LOCKBP_PLUGIN_TEXT_DOMAIN )?>" onclick="window.open('<?php echo $loginurl;?>','_blank')">
		<?php if ( get_option( 'users_can_register' ) ) {?>
			<input type="button" value="<?php _e( 'Register', LOCKBP_PLUGIN_TEXT_DOMAIN )?>" onclick="window.open('<?php echo $registerurl;?>','_blank')">
		<?php }?>
	</div>
</div>
<?php get_footer(); ?>