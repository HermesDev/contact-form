<?php 

Plugin::init();

/**
 * Plugin WordPress Plugin Entry point
 */
class Plugin {
  public static function init() {
  	add_action ('init', array(__CLASS__, 'init_tasks'));
		add_action('admin_init', array(__CLASS__, 'admin_init_tasks'));
		add_action('wp_footer', array(__CLASS__, 'wp_footer_tasks'));
		add_action('admin_menu', array(__CLASS__, 'wp_admin_menu_tasks'));
  }

  /**
   * init_tasks Tasks related to WordPress init action
   */
  public static function init_tasks() {
  	wp_enqueue_script('validation_js', 
  		WEBSITE_SNAPSHOT__PLUGIN_URL.'node_modules/validate-js/validate.min.js', null, null, false);
  	wp_enqueue_script('user_feedback_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'js/user-feedback.js', 
  		null, null, false);
  	wp_enqueue_style('hermesdev_contact_form_css', WEBSITE_SNAPSHOT__PLUGIN_URL.'css/style.css');

  	add_shortcode('hermes_contact_form', array(__CLASS__, 'show_contact_form_view')); // Contact form shortcode
  }

  /**
   * admin_init_tasks Tasks related to WordPress admin_init action
   */
  public static function admin_init_tasks() {
  	wp_enqueue_script('hermesdev_contact_form_admin_settings_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'js/admin-settings.js', 
  		null, null, true);
  }

  /**
   * wp_footer_tasks Tasks related to WordPress wp_footer action
   */
  public static function wp_footer_tasks() {
  	wp_enqueue_script('hermesdev_contact_form_js', WEBSITE_SNAPSHOT__PLUGIN_URL.'js/contact-form.js', 
  		null, null, true);
  }

  /**
   * wp_admin_menu_tasks Tasks related to WordPress wp_admin_menu action
   */
  public static function wp_admin_menu_tasks() {
  	add_options_page(PLUGIN_NAME, PLUGIN_NAME, 'manage_options', __FILE__, 
  		array(__CLASS__, 'show_settings_view'));
  }

  /**
   * show_contact_form_view Show the main view with the contact form
   */
	public static function show_contact_form_view() {
		include dirname(__FILE__).'/../../views/_template.contact-form.php';
	}

	/**
	 * show_settings_view Show the admin view with the contact form settings
	 */
	public static function show_settings_view() {
		include dirname(__FILE__).'/../../views/_template.admin-settings.php';
	}
}
