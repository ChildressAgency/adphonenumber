<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('APN_Options_Page')){

  class APN_Options_Page{
    private $options = array();

    public function __construct(){
      $this->options['default_phone_number'] = get_option('default_phone_number');
      $this->options['use_url_parameter'] = get_option('use_url_parameter');
      $this->options['url_parameter'] = get_option('url_parameter');
      $this->options['url_parameter_value'] = get_option('url_parameter_value');
      $this->options['ad_phone_number_url'] = get_option('ad_phone_number_url');
      $this->options['cookie_lifespan'] = get_option('cookie_lifespan');
      
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

    public function create_admin_page(){ ?>

      <div class="wrap">
        <h1><?php esc_html_e('Advertising Phone Number and Usage', 'ad_phone_number'); ?></h1>
        <form method="post" action="options.php">
          <?php
            settings_fields('apn_settings_section');
            do_settings_sections('apn-settings');
            submit_button();
          ?>
        </form>
        <h2><?php esc_html_e('Usage:', 'ad_phone_number'); ?></h2>
        <p><?php echo esc_html__('There are 3 ways to set the advertising phone number:', 'ad_phone_number'); ?></p>
        <p><?php echo esc_html__('First the plugin will look for a cookie already set, that hasn\'t expired, and will use that phone number. You can set how long a cookie lasts in the field above.', 'ad_phone_number'); ?></p>
        <p><?php echo esc_html__('Second, if the "Use URL Parameter" box is checked above, it will look for the parameter and parameter value you set in the fields. If that parameter exists, it will use the phone number entered in the field above.', 'ad_phone_number'); ?></p>
        <p><?php echo esc_html__('Third, it will look for the "Is this an Advertisement Landing page?" box to be checked on the page visited then use the phone number entered on that page.', 'ad_phone_number'); ?></p>
        <p><?php echo esc_html__('Once the plugin determines it needs to use a replacement phone number for an ad, and it determines which phone number to use, each <a> element that has href="tel: will be updated with the ad phone number.', 'ad_phone_number');  ?></p>
        <p><?php echo esc_html__('You can also use a shortcode in place of hard-coding the <a> element.  The element output by the shortcode will be updated the same way as a regular element would.', 'ad_phone_number'); ?></p>
      </div>

      <?php
    }

    public function page_init(){
      add_settings_section(
        'apn_settings_section',
        '',
        array($this, 'print_section_info'),
        'apn-settings'
      );

      //default phone number field
      register_setting(
        'apn_settings_section',
        'default_phone_number',
        array($this, 'sanitize')
      );
      add_settings_field(
        'default_phone_number',
        esc_html__('Default Phone Number', 'ad_phone_number'),
        array($this, 'default_phone_number_callback'),
        'apn-settings',
        'apn_settings_section'
      );

      //use url parameter option field
      register_setting(
        'apn_settings_section',
        'use_url_parameter',
        array($this, 'sanitize')
      );
      add_settings_field(
        'use_url_parameter',
        esc_html__('Use URL Parameter', 'ad_phone_number'),
        array($this, 'use_url_parameter_callback'),
        'apn-settings', 
        'apn_settings_section'
      );

      //url parameter name field
      register_setting(
        'apn_settings_section',
        'url_parameter',
        array($this, 'sanitize')
      );
      add_settings_field(
        'url_parameter',
        esc_html__('Enter the URL Parameter to look for.', 'ad_phone_number'),
        array($this, 'url_parameter_callback'),
        'apn-settings',
        'apn_settings_section'
      );

      //url parameter value
      register_setting(
        'apn_settings_section',
        'url_parameter_value',
        array($this, 'sanitize')
      );
      add_settings_field(
        'url_parameter_value',
        esc_html__('Enter the value of URL Parameter to look for.', 'ad_phone_number'),
        array($this, 'url_parameter_value_callback'),
        'apn-settings',
        'apn_settings_section'
      );

      //ad phone number when using url parameter
      register_setting(
        'apn_settings_section',
        'ad_phone_number_url',
        array($this, 'sanitize')
      );
      add_settings_field(
        'ad_phone_number_url',
        esc_html__('Enter the advertising phone number.', 'ad_phone_number'),
        array($this, 'ad_phone_number_url_callback'),
        'apn-settings',
        'apn_settings_section'
      );

      //cookie lifespan
      register_setting(
        'apn_settings_section',
        'cookie_lifespan',
        array($this, 'sanitize')
      );
      add_settings_field(
        'cookie_lifespan',
        esc_html__('Enter how long in days the cookie should save the phone number.', 'ad_phone_number'),
        array($this, 'cookie_lifespan_callback'),
        'apn-settings',
        'apn_settings_section'
      );
    }

    public function sanitize($input){
      $new_input = sanitize_text_field($input);

      return $new_input;
    }

    public function print_section_info(){
      printf(esc_html__('Ad Phone Number Settings', 'ad_phone_number'));
    }

    public function default_phone_number_callback(){
      printf(
        '<input type="text" id="default_phone_number" name="default_phone_number" value="%s" />',
        isset($this->options['default_phone_number']) ? esc_attr($this->options['default_phone_number']) : ''
      );
    }

    public function use_url_parameter_callback(){
      printf(
        '<input type="checkbox" id="use_url_parameter" name="use_url_parameter" value="1" %s />',
        isset($this->options['use_url_parameter']) && $this->options['use_url_parameter'] == 1 ? esc_attr('checked') : ''
      );
    }

    public function url_parameter_callback(){
      printf(
        '<input type="text" id="url_parameter" name="url_parameter" value="%s" />',
        isset($this->options['url_parameter']) ? esc_attr($this->options['url_parameter']): ''
      );
    }

    public function url_parameter_value_callback(){
      printf(
        '<input type="text" id="url_parameter_value" name="url_parameter_value" value="%s" />',
        isset($this->options['url_parameter_value']) ? esc_attr($this->options['url_parameter_value']): ''
      );
    }

    public function ad_phone_number_url_callback(){
      printf(
        '<input type="text" id="ad_phone_number_url" name="ad_phone_number_url" value="%s" />',
        isset($this->options['ad_phone_number_url']) ? esc_attr($this->options['ad_phone_number_url']) : ''
      );
    }

    public function cookie_lifespan_callback(){
      printf(
        '<input type="text" id="cookie_lifespan" name="cookie_lifespan" value="%s" />',
        isset($this->options['cookie_lifespan']) ? esc_attr($this->options['cookie_lifespan']) : ''
      );
    }
  }
}