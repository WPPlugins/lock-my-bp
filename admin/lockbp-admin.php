<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Add admin page for displaying wordpress + buddypress locking settings
if( !class_exists( 'lockbp_AdminPage' ) ) {
	class lockbp_AdminPage{

		private $plugin_slug = 'lock-my-bp',
				$plugin_settings_tabs = array();

		//constructor
		function __construct() {
			add_action( 'admin_menu', array( $this, 'lockbp_add_menu_page' ) );

			add_action('admin_init', array($this, 'lockbp_register_general_settings'));
			add_action('admin_init', array($this, 'lockbp_register_support_settings'));
		}

		//Actions performed to create a custom menu on loading admin_menu
		function lockbp_add_menu_page() {
			add_menu_page( __( 'Lock Settings', LOCKBP_PLUGIN_TEXT_DOMAIN ), __( 'Lock', LOCKBP_PLUGIN_TEXT_DOMAIN ), 'manage_options', $this->plugin_slug, array( $this, 'lockbp_admin_settings_page' ), 'dashicons-lock', 3 );
		}

		function lockbp_admin_settings_page() {
			$tab = isset($_GET['tab']) ? $_GET['tab'] : 'lock-my-bp';?>
			<div class="wrap">
				<h2><?php _e('WordPress Locking', LOCKBP_PLUGIN_TEXT_DOMAIN); ?></h2>
				<p><?php _e('This plugin will allow you to lock down several pages and buddypress components (if active).', LOCKBP_PLUGIN_TEXT_DOMAIN); ?></p>
				<?php $this->lockbp_plugin_settings_tabs(); ?>
				<form action="" method="POST" id="<?php echo $tab;?>-settings-form">
				<?php do_settings_sections( $tab );?>
				</form>
			</div>
			<?php
		}

		function lockbp_plugin_settings_tabs() {
			$current_tab = isset($_GET['tab']) ? $_GET['tab'] : 'lock-my-bp';
			echo '<h2 class="nav-tab-wrapper">';
			foreach ($this->plugin_settings_tabs as $tab_key => $tab_caption) {
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->plugin_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		function lockbp_register_general_settings() {
			$this->plugin_settings_tabs['lock-my-bp'] = __( 'General', LOCKBP_PLUGIN_TEXT_DOMAIN );
			register_setting('lock-my-bp', 'lock-my-bp');
			add_settings_section('lockbp-general-section', ' ', array(&$this, 'lockbp_section_general'), 'lock-my-bp');
		}

		function lockbp_section_general() {
			if (file_exists(dirname(__FILE__) . '/lockbp-general-settings.php')) {
				require_once( dirname(__FILE__) . '/lockbp-general-settings.php' );
			}
		}

		function lockbp_register_support_settings() {
			$this->plugin_settings_tabs['lockbp-support'] = __( 'Support', LOCKBP_PLUGIN_TEXT_DOMAIN );
			register_setting('lockbp-support', 'lockbp-support');
			add_settings_section('section_support', ' ', array(&$this, 'lockbp_section_support'), 'lockbp-support');
		}

		function lockbp_section_support() {
			if (file_exists(dirname(__FILE__) . '/lockbp-support.php')) {
				require_once( dirname(__FILE__) . '/lockbp-support.php' );
			}
		}
	}
	new lockbp_AdminPage();
}