<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Save admin settings
if (isset($_POST['lockbp-general-settings-submit']) && wp_verify_nonce($_POST['lockbp_settings_save_nonce'], 'lockbp')) {
	//Lock BP Components
	if(!empty($_POST['lockbp_bp_component'])){
		$bp_components_to_lock = filter_var_array($_POST['lockbp_bp_component'], FILTER_SANITIZE_STRING);
		$lockbp_settings['locked_bp_components'] = $bp_components_to_lock;
	}

	//Lock CPTs
	if(!empty($_POST['lockbp_cpt'])){
		$cpts_to_lock = filter_var_array($_POST['lockbp_cpt'], FILTER_SANITIZE_STRING);
		$lockbp_settings['locked_cpts'] = $cpts_to_lock;
	}

	//Locking WP Pages
	if(!empty($_POST['lockbp_wp_pages'])){
		$pages_to_lock = filter_var_array($_POST['lockbp_wp_pages'], FILTER_SANITIZE_STRING);
		$lockbp_settings['locked_pages'] = $pages_to_lock;
	}

	//Save the locking content
	$lock_content = array(
		'type' => sanitize_text_field($_POST['lockbp_display_items']),
		'content' => sanitize_text_field($_POST['lockbp_display'])
	);
	$lockbp_settings['locked_content'] = $lock_content;
	//Update the save
	update_option('lockbp_settings', $lockbp_settings);

	//Save Success Message
	$msg = '<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated">';
	$msg .= '<p><strong>'.__( 'Lock BuddyPress Settings Saved.', LOCKBP_PLUGIN_TEXT_DOMAIN ).'</strong></p>';
	$msg .= '<button class="notice-dismiss" type="button">';
	$msg .= '<span class="screen-reader-text">Dismiss this notice.</span>';
	$msg .= '</button>';
	$msg .= '</div>';
	echo $msg;
}

//BP Compnents
$bp_components = array(
	'members' => 'Members',
	'groups' => 'Groups',
	'activity' => 'Activity',
	'profile' => 'Profile',
	'friends' => 'Friends',
	'notifications' => 'Notifications'
);
//All WP Pages
$pages = get_pages();
$bp_pages = '';
if( LOCKBP_BP_ACTIVE ) {
	$bp_pages = get_option('bp-pages');
}
foreach( $pages as $page ) {
	$wp_pages[$page->ID] = $page->post_title;
}
foreach( $wp_pages as $pgid => $pg_title ) {
	if( $bp_pages && in_array( $pgid, $bp_pages ) ) {
		unset( $wp_pages[$pgid] );
	}
}

//All custom post types
$args = array( 'public' => true, '_builtin' => false );
$cpts = get_post_types($args, 'objects');

//Retrieve Settings
$lockbp_settings = get_option('lockbp_settings');

$style = '';
if (!isset($lockbp_settings['locked_content'])) $style = "display: none;";
?>
<div class="wrap">
	<h3><?php _e("General Settings", "bp-fitness"); ?></h3>
	<div class="lockbp-general-settings-container">
		<table class="form-table">
			<tbody>
				<!-- BP COMPONENTS -->
				<tr>
					<th scope="row">
						<label for="bp-components"><?php _e( 'BuddyPress Components', LOCKBP_PLUGIN_TEXT_DOMAIN );?></label>
						<p class="lockbp-description"><?php _e( 'Please select BuddyPress Components which you would like to lock.', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
					</th>
					<td>
						<?php if( LOCKBP_BP_ACTIVE ) {?>
							<div class="lockbp-elements">
								<select name="lockbp_bp_component[]" multiple>
									<?php foreach ($bp_components as $slug => $bp_component) {?>
									<option value="<?php echo $slug; ?>" <?php if (isset($lockbp_settings['locked_bp_components']) && in_array($slug, $lockbp_settings['locked_bp_components'])) echo 'selected="selected"'; ?>><?php echo $bp_component; ?></option>
									<?php }?>
								</select>
							</div>
						<?php } else {?>
								<p class="lockbp-bp-not-found"><?php _e( 'Please activate BuddyPress plugin first!', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
						<?php }?>
					</td>
				</tr>

				<!-- CUSTOM POST TYPES -->
				<tr>
					<th scope="row">
						<label for="cpts"><?php _e( 'Custom Post Types', LOCKBP_PLUGIN_TEXT_DOMAIN );?></label>
						<p class="lockbp-description"><?php _e( 'Please select Custom Post Types which you would like to lock.', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
					</th>
					<td>
						<?php if (!empty($cpts)) {?>
							<div class="lockbp-elements">
								<select name="lockbp_cpt[]" multiple>
									<?php foreach ($cpts as $slug => $cpt) {?>
									<option value="<?php echo $slug; ?>" <?php if (isset($lockbp_settings['locked_cpts']) && in_array($slug, $lockbp_settings['locked_cpts'])) echo 'selected="selected"'; ?>><?php echo $cpt->label; ?></option>
									<?php }?>
								</select>
							</div>
						<?php } else {?>
							<p class="lockbp-cpt-not-found"><?php _e( 'You do not have any custom post types registered!', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
						<?php }?>
					</td>
				</tr>

				<!-- WORDPRESS PAGES -->
				<tr>
					<th scope="row">
						<label for="wp-pages"><?php _e( 'Pages', LOCKBP_PLUGIN_TEXT_DOMAIN );?></label>
						<p class="lockbp-description"><?php _e( 'Please select Pages which you would like to lock.', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
					</th>
					<td>
						<?php if (!empty($wp_pages)) {?>
							<div class="lockbp-elements">
								<select name="lockbp_wp_pages[]" multiple>
									<?php foreach ( $wp_pages as $pgid => $pg_title ) {?>
									<option value="<?php echo $pgid;?>" <?php if (isset($lockbp_settings['locked_pages']) && in_array($pgid, $lockbp_settings['locked_pages'])) echo 'selected="selected"';?>><?php echo $pg_title;?></option>
									<?php }?>
								</select>
							</div>
						<?php } else {?>
							<p class="lockbp-page-not-found"><?php _e( 'No WP page found!', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
						<?php }?>
					</td>
				</tr>

				<!-- LOCK DISPLAY -->
				<tr>
					<th scope="row">
						<label for="wp-pages"><?php _e( 'Locked Template', LOCKBP_PLUGIN_TEXT_DOMAIN );?></label>
						<p class="lockbp-description"><?php _e( 'What Should Be Displayed.', LOCKBP_PLUGIN_TEXT_DOMAIN );?></p>
					</th>
					<td>
						<div class="lockbp-elements">
							<select required name="lockbp_display_items" id="lockbp-display-items">
								<option value=""><?php _e( '--Select--', LOCKBP_PLUGIN_TEXT_DOMAIN );?></option>
								<option value="shortcode" <?php if (isset($lockbp_settings['locked_content']) && $lockbp_settings['locked_content']['type'] == 'shortcode') echo 'selected="selected"'; ?>><?php _e( 'Shortcode', LOCKBP_PLUGIN_TEXT_DOMAIN );?></option>
								<option value="message" <?php if (isset($lockbp_settings['locked_content']) && $lockbp_settings['locked_content']['type'] == 'message') echo 'selected="selected"'; ?>><?php _e( 'Message', LOCKBP_PLUGIN_TEXT_DOMAIN );?></option>
							</select>
						</div>
						<div class="lockbp-elements" style="<?php echo $style; ?>">
							<textarea name="lockbp_display" rows="4" placeholder="<?php _e( 'Please enter your message which you would like to diplay logout users', LOCKBP_PLUGIN_TEXT_DOMAIN );?>"><?php if (isset($lockbp_settings['locked_content'])) echo $lockbp_settings['locked_content']['content']; ?></textarea>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
		<?php wp_nonce_field( 'lockbp', 'lockbp_settings_save_nonce'); ?>
		<p class="submit"><input type="submit" name="lockbp-general-settings-submit" class="button button-primary" value="<?php _e("Save Changes","bp-fitness"); ?>"></p>
	</div>
</div>