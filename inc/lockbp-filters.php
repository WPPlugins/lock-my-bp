<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

//Custom Hooks
if (!class_exists('Lock_BP_Hooks')) {
  class Lock_BP_Hooks {
    /**
     * Constructor
     */
    public function __construct() {
      /* change wp page template */
      add_filter('page_template', array($this, 'lockbp_page_template'));
      add_filter('archive_template', array($this, 'lockbp_cpt_template'));
      add_filter('single_template', array($this, 'lockbp_cpt_single_template'));

      /* change bp component page template */
      add_filter( 'bp_located_template', array($this,'lockbp_component_template_filter'), 10, 2 );
    }

    /**
    * Actions performed to show the lock template for BP component pages
    */
    public function lockbp_component_template_filter( $found_template, $templates ) {
      global $post, $bp;
      $lockbp_settings      = get_option('lockbp_settings');
      $filtered_templates   = array();
      $filtered_templates[] = LOCKBP_PLUGIN_PATH .'inc/templates/lockbp-lock.php';
      if( LOCKBP_BP_ACTIVE ) {
        //Lock BP Components
        if (isset($lockbp_settings['locked_bp_components']) && in_array($bp->current_component, $lockbp_settings['locked_bp_components']) && !is_user_logged_in()) {
            $found_template = $filtered_templates[0];
        }
      }
      return apply_filters( 'lockbp_component_template_filter', $found_template );
    }

    /**
     * Actions performed to show the lock template for wp pages
     */
    public function lockbp_page_template($template) {
      //echo $template; die;
      global $post, $bp;
      $lockbp_settings  = get_option('lockbp_settings');
      $lock_bp_template = LOCKBP_PLUGIN_PATH . 'inc/templates/lockbp-lock.php';
      //Lock WP Pages
      if (isset($lockbp_settings['locked_pages']) && in_array($post->ID, $lockbp_settings['locked_pages']) && !is_user_logged_in()) {
        if (file_exists($lock_bp_template)) {
          $template = $lock_bp_template;
        }
      }
      return $template;
    }

    /**
     * Actions performed to show the lock template for cpts
     */
    public function lockbp_cpt_template($template) {
      global $post;
      $lockbp_settings = get_option('lockbp_settings');
      if (is_archive()) {
        if (isset($lockbp_settings['locked_cpts']) && in_array($post->post_type, $lockbp_settings['locked_cpts']) && !is_user_logged_in()) {
          $lock_bp_template = LOCKBP_PLUGIN_PATH . 'inc/templates/lockbp-lock.php';
          if (file_exists($lock_bp_template)) {
            $template = $lock_bp_template;
          }
        }
      }
      return $template;
    }

    /**
     * Actions performed to show the lock template for cpts single pages
     */
    public function lockbp_cpt_single_template($template) {
      global $post;
      $lockbp_settings = get_option('lockbp_settings');
      if (is_single()) {
        if (isset($lockbp_settings['locked_cpts']) && in_array($post->post_type, $lockbp_settings['locked_cpts']) && !is_user_logged_in()) {
          $lock_bp_template = LOCKBP_PLUGIN_PATH . 'inc/templates/lockbp-lock.php';
          if (file_exists($lock_bp_template)) {
            $template = $lock_bp_template;
          }
        }
      }
      return $template;
    }
  }
  new Lock_BP_Hooks();
}
