<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('APN_Options_Page')){

  class APN_Options_Page{
    private $options;

    public function __construct(){
      add_action('admin_menu', array($this, 'add_plugin_page'));
      add_action('admin_init', array($this, 'page_init'));
    }

    public function add_plugin_page(){
      add_menu_page(
        esc_html__('Ad Phone Number', 'ad_phone_number'),
        esc_html__('Ad Phone Number', 'ad_phone_number'),
        'manage_options',
        'apn-settings',
        array($this, 'create_admin_page'),
        'dashicons-phone',
        81
      );
    }

    public function create_admin_page(){
      $this->options = get_option('default_phone_number'); ?>

      <div class="wrap">
        <h1><?php esc_html_e('Advertising Phone Number and Usage', 'ad_phone_number'); ?></h1>
        <form method="post" action="options.php">
          <?php
            settings_fields('default-phone-number-group');
            do_settings_sections('apn-settings');
            submit_button();
          ?>
        </form>
        <h2><?php esc_html_e('Usage:', 'ad_phone_number'); ?></h2>
        <p><?php esc_html_e('This plugin will allow businesses to track new leads by connecting phone numbers to Google Ads.  Normally if a user clicks a Google Ad to go to the business\'s website, they see the default phone number for the business and when they call, the business doesn\'t know if the lead came from the advertisement or organically.', 'ad_phone_number'); ?></p>
        <p><?php esc_html_e('With this plugin the business creates a landing page then assigns a phone number to it. The Google Ad then links to this landing page. When the user lands they receive a cookie with the phone number specific to the landing page and that particular ad - then all phone numbers displayed on the site are changed according to the phone number in the cookie. So even if a user navigates around the site or comes back to it at a later date, they still get the phone number related to the Google Ad and the business still know exactly where the call lead came from.', 'ad_phone_number'); ?></p>
        <p><?php esc_html_e('By default the cookie last for 30 days but this can be changed using the settings above. Also when a user click on another Google Ad for the same business but takes them to a different landing page, the cookie stores that phone number instead - so the phone number from the most recent Ad they clicked on will show everywhere on the site.', 'ad_phone_number'); ?></p>
        <p><?php esc_html_e('For a phone number to be updated to Ad Phone Number if must have the href="tel:" attribute set. You can set all of your telephone links using the shortcode [apn_phone_number_link]. This will either display the website site\'s default phone number (set using the field above) or the Ad\'s phone number. You can add a class to the phone number link using the "class" attribute. For example, if you set the default phone number above to (123) 123-1234:', 'ad_phone_number'); ?></p>
        <code>[apn_phone_number_link class="website-link tel-link"]</code>
        <p><?php esc_html_e('Will put:', 'ad_phone_number'); ?></p>
        <code>&lt;a href=&quot;tel:(123) 123-3214&quot; class=&quot;website-link tel-link&quot;&gt;(123) 123-1234&lt;&#47a&gt;</code>
        <p><?php esc_html_e('on the page and render as:', 'ad_phone_number'); ?></p>
        <code><a href="tel:(123) 123-1234" class="website-link tel-link">(123) 123-1234</a></code>
        <p><?php esc_html_e('in the browser.', 'ad_phone_number'); ?></p>
        <p><?php esc_html_e('Even if you "hardcode" a telephone number link, if it has the href="tel:" attribute, it will be updated with the Advertisement Phone Number.', 'ad_phone_number'); ?></p>
        <p><?php esc_html_e('To assign a phone number to a landing page, check the box "Is this an Advertisement Landing Page?" and enter a phone number (if you leave the phone number blank the default phone number will show instead).', 'ad_phone_number'); ?></p>
      </div>

      <?php
    }

    public function page_init(){
      register_setting(
        'default-phone-number-group',
        'default_phone_number',
        array($this, 'sanitize')
      );

      add_settings_section(
        'default_phone_number_section_id',
        '',
        array($this, 'print_section_info'),
        'apn-settings'
      );

      add_settings_field(
        'default_phone_number',
        esc_html__('Default Phone Number', 'ad_phone_number'),
        array($this, 'default_phone_number_callback'),
        'apn-settings',
        'default_phone_number_section_id'
      );
    }

    public function sanitize($input){
      $new_input = sanitize_text_field($input);

      return $new_input;
    }

    public function print_section_info(){
      printf(esc_html__('Enter the default Phone Number', 'ad_phone_number'));
    }

    public function default_phone_number_callback(){
      printf(
        '<input type="text" id="default_phone_number" name="default_phone_number" value="%s" />',
        isset($this->options) ? esc_attr($this->options) : ''
      );
    }
  }
}