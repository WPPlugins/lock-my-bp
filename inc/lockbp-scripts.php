<?php
defined('ABSPATH') || exit; // Exit if accessed directly

//Class to add custom scripts and styles
if ( !class_exists( 'Lock_BP_Scripts_Styles' ) ) {
     class Lock_BP_Scripts_Styles {
          //Constructor
          public function __construct() {
               add_action( 'wp_enqueue_scripts', array( $this, 'lockbp_custom_variables' ) );
               if (stripos($_SERVER['REQUEST_URI'], 'lock-my-bp') !== false) {
                    add_action('admin_enqueue_scripts', array($this, 'lockbp_admin_variables'));
               }
          }

          //Actions performed for enqueuing scripts and styles for front end.
          public function lockbp_custom_variables() {
               if( !is_user_logged_in() ){
                    wp_enqueue_style('lockbp-css-front', LOCKBP_PLUGIN_URL . 'assets/public/css/lockbp-front.css');
                    wp_enqueue_script('lockbp-js-front', LOCKBP_PLUGIN_URL . 'assets/public/js/lockbp-front.js', array('jquery'));
                    
               }  
          }

          //Actions performed for enqueuing scripts and styles for admin panel.
          public function lockbp_admin_variables() {
               wp_enqueue_style('lockbp-admin-css', LOCKBP_PLUGIN_URL . 'assets/admin/css/lockbp-admin.css');
               wp_enqueue_script('lockbp-admin-js', LOCKBP_PLUGIN_URL . 'assets/admin/js/lockbp-admin.js', array('jquery'));
          }
     }
     new Lock_BP_Scripts_Styles();
}
